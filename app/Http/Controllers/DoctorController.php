<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\DoctorProfile;
use App\Models\DoctorAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $doctors = User::where('role', 'doctor')
            ->with(['doctorProfile.clinic', 'doctorProfile.specialty'])
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%"))
            ->paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $clinics = Clinic::where('is_active', true)->get();
        $specialties = Specialty::where('is_active', true)->get();
        return view('doctors.create', compact('clinics', 'specialties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'clinic_id' => 'required|exists:clinics,id',
            'specialty_id' => 'required|exists:specialties,id',
            'license_number' => 'nullable|string',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'doctor',
            ]);

            $user->doctorProfile()->create([
                'clinic_id' => $validated['clinic_id'],
                'specialty_id' => $validated['specialty_id'],
                'license_number' => $validated['license_number'],
                'phone' => $validated['phone'],
                'bio' => $validated['bio'],
            ]);
        });

        return redirect()->route('doctors.index')->with('success', 'Doctor created.');
    }

    public function edit(User $doctor)
    {
        $doctor->load('doctorProfile');
        $clinics = Clinic::all();
        $specialties = Specialty::all();
        return view('doctors.edit', compact('doctor', 'clinics', 'specialties'));
    }

    public function update(Request $request, User $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$doctor->id,
            'password' => 'nullable|min:8',
            'clinic_id' => 'required|exists:clinics,id',
            'specialty_id' => 'required|exists:specialties,id',
            'license_number' => 'nullable|string',
            'phone' => 'nullable|string',
            'bio' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        DB::transaction(function () use ($validated, $doctor) {
            $doctor->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if ($validated['password']) {
                $doctor->update(['password' => Hash::make($validated['password'])]);
            }

            $doctor->doctorProfile()->update([
                'clinic_id' => $validated['clinic_id'],
                'specialty_id' => $validated['specialty_id'],
                'license_number' => $validated['license_number'],
                'phone' => $validated['phone'],
                'bio' => $validated['bio'],
                'is_active' => $validated['is_active'],
            ]);
        });

        return redirect()->route('doctors.index')->with('success', 'Doctor updated.');
    }

    public function destroy(User $doctor)
    {
        $doctor->doctorProfile()->update(['is_active' => false]);
        return redirect()->route('doctors.index')->with('success', 'Doctor deactivated.');
    }

    public function availability(User $doctor)
    {
        $availabilities = $doctor->doctorAvailabilities()->orderBy('day_of_week')->get();
        return view('doctors.availability', compact('doctor', 'availabilities'));
    }

    public function availabilityStore(Request $request, User $doctor)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $doctor->doctorAvailabilities()->create($validated);
        return back()->with('success', 'Availability added.');
    }

    public function availabilityDestroy(User $doctor, DoctorAvailability $availability)
    {
        $availability->delete();
        return back()->with('success', 'Availability removed.');
    }
}