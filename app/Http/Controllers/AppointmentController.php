<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\DoctorAvailability;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create(): View
    {
        $patients = Patient::orderBy('first_name')->get();

        $doctors = User::where('role', 'doctor')
            ->whereHas('doctorProfile', fn ($q) => $q->where('is_active', true))
            ->with(['doctorProfile.specialty', 'doctorAvailabilities'])
            ->orderBy('name')
            ->get();

        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',

            'doctor_id' => [
                'required',
                'exists:users,id',
                function ($attr, $value, $fail) {
                    $ok = User::where('id', $value)
                        ->where('role', 'doctor')
                        ->whereHas('doctorProfile', fn ($q) => $q->where('is_active', true))
                        ->exists();

                    if (!$ok) {
                        $fail('Selected doctor is not active or not valid.');
                    }
                }
            ],

            'appointment_date' => 'required|date|after:now',
            'status' => 'required|in:scheduled,completed,cancelled,no-show',
            'notes' => 'nullable|string',
        ]);

        $dateTime = Carbon::parse($validated['appointment_date']);

        // ✅ enforce 15-minute slots
        if (!$this->isValidFifteenMinuteSlot($dateTime)) {
            return back()->withInput()->withErrors([
                'appointment_date' => 'Appointments must be scheduled in 15-minute slots (e.g., 10:00, 10:15, 10:30, 10:45).'
            ]);
        }

        if (!$this->isDoctorAvailable($validated['doctor_id'], $dateTime)) {
            return back()->withInput()->withErrors([
                'appointment_date' => 'Doctor is not available at this time.'
            ]);
        }

        if ($this->hasOverlapInSlot($validated['doctor_id'], $dateTime)) {
            return back()->withInput()->withErrors([
                'appointment_date' => 'This 15-minute slot is already booked.'
            ]);
        }

        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',

            'doctor_id' => [
                'required',
                'exists:users,id',
                function ($attr, $value, $fail) {
                    $ok = User::where('id', $value)
                        ->where('role', 'doctor')
                        ->whereHas('doctorProfile', fn ($q) => $q->where('is_active', true))
                        ->exists();

                    if (!$ok) {
                        $fail('Selected doctor is not active or not valid.');
                    }
                }
            ],

            'appointment_date' => 'required|date|after:now',
            'status' => 'required|in:scheduled,completed,cancelled,no-show',
            'notes' => 'nullable|string',
        ]);

        $dateTime = Carbon::parse($validated['appointment_date']);

        if (!$this->isValidFifteenMinuteSlot($dateTime)) {
            return back()->withInput()->withErrors([
                'appointment_date' => 'Appointments must be scheduled in 15-minute slots.'
            ]);
        }

        if (
            $appointment->doctor_id != $validated['doctor_id'] ||
            !$appointment->appointment_date->equalTo($dateTime)
        ) {
            if (!$this->isDoctorAvailable($validated['doctor_id'], $dateTime)) {
                return back()->withErrors(['appointment_date' => 'Doctor not available.']);
            }

            if ($this->hasOverlapInSlot($validated['doctor_id'], $dateTime, $appointment->id)) {
                return back()->withErrors(['appointment_date' => 'This slot is already booked.']);
            }
        }

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /* ===================== HELPERS ===================== */

    private function isDoctorAvailable($doctorId, Carbon $dateTime): bool
    {
        return DoctorAvailability::where('user_id', $doctorId)
            ->where('day_of_week', $dateTime->dayOfWeek)
            ->where('start_time', '<=', $dateTime->format('H:i:s'))
            ->where('end_time', '>', $dateTime->format('H:i:s'))
            ->where('is_active', true)
            ->exists();
    }

    private function isValidFifteenMinuteSlot(Carbon $dateTime): bool
    {
        return in_array((int)$dateTime->format('i'), [0, 15, 30, 45], true);
    }

    private function hasOverlapInSlot($doctorId, Carbon $dateTime, $excludeId = null): bool
    {
         $slotStart = $dateTime->copy()->second(0);
    $minute = (int) $slotStart->format('i');

    // floor to nearest 15-minute slot
    $slotStart->minute(intdiv($minute, 15) * 15);

    $slotEnd = $slotStart->copy()->addMinutes(15);

    return Appointment::where('doctor_id', $doctorId)
        ->where('status', 'scheduled')
        ->where('appointment_date', '>=', $slotStart)
        ->where('appointment_date', '<', $slotEnd)
        ->when($excludeId, function ($query) use ($excludeId) {
            return $query->where('id', '!=', $excludeId);
        })
        ->exists();
    }
}
