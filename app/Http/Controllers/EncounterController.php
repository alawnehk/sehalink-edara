<?php

namespace App\Http\Controllers;

use App\Models\Encounter;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EncounterController extends Controller
{
    public function index(): View
    {
        $encounters = Encounter::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('encounters.index', compact('encounters'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = User::where('role', 'doctor')->get();
        $appointments = Appointment::whereDoesntHave('encounter')->get();

        return view('encounters.create', compact('patients', 'doctors', 'appointments'));
    }

    public function createFromAppointment(Appointment $appointment): View
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = User::where('role', 'doctor')->get();
        
        return view('encounters.create', [
            'patients' => $patients,
            'doctors' => $doctors,
            'prefilled_appointment' => $appointment
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'chief_complaint' => 'required|string|max:255',
            'hpi' => 'nullable|string',
            'vitals' => 'nullable|array',
            'diagnosis' => 'nullable|string',
            'plan' => 'nullable|string',
        ]);

        Encounter::create($validated);

        return redirect()->route('encounters.index')->with('success', 'Encounter started successfully.');
    }

    public function show(Encounter $encounter): View
    {
        return view('encounters.show', compact('encounter'));
    }

    public function edit(Encounter $encounter): View
    {
        if ($encounter->status === 'closed') {
            return redirect()->route('encounters.show', $encounter)->with('error', 'Closed encounters cannot be edited.');
        }

        $patients = Patient::orderBy('first_name')->get();
        $doctors = User::where('role', 'doctor')->get();

        return view('encounters.edit', compact('encounter', 'patients', 'doctors'));
    }

    public function update(Request $request, Encounter $encounter): RedirectResponse
    {
        if ($encounter->status === 'closed') {
            return back()->with('error', 'Cannot update a closed encounter.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'chief_complaint' => 'required|string|max:255',
            'hpi' => 'nullable|string',
            'vitals' => 'nullable|array',
            'diagnosis' => 'nullable|string',
            'plan' => 'nullable|string',
        ]);

        $encounter->update($validated);

        return redirect()->route('encounters.show', $encounter)->with('success', 'Encounter updated.');
    }

    public function close(Encounter $encounter): RedirectResponse
    {
        $encounter->update(['status' => 'closed']);
        return back()->with('success', 'Encounter closed successfully.');
    }
}