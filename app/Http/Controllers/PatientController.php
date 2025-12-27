<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('medical_record_number', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'medical_record_number' => 'required|string|max:50|unique:patients,medical_record_number',
            'first_name'           => 'required|string|max:100',
            'last_name'            => 'required|string|max:100',
            'gender'               => 'required|in:male,female',
            'date_of_birth'        => 'required|date',
            'phone'                => 'required|string|max:50',
            'national_id'          => 'nullable|string|max:50',
            'address'              => 'nullable|string|max:255',
        ]);

        Patient::create($data);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient created successfully.');
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'medical_record_number' => 'required|string|max:50|unique:patients,medical_record_number,' . $patient->id,
            'first_name'           => 'required|string|max:100',
            'last_name'            => 'required|string|max:100',
            'gender'               => 'required|in:male,female',
            'date_of_birth'        => 'required|date',
            'phone'                => 'required|string|max:50',
            'national_id'          => 'nullable|string|max:50',
            'address'              => 'nullable|string|max:255',
        ]);

        $patient->update($data);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
