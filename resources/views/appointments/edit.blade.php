<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Appointment #{{ $appointment->id }}
            </h2>

            <a href="{{ route('appointments.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md font-semibold text-sm shadow
                      hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-800">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Patient -->
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                            <select id="patient_id" name="patient_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Patient --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ (string)old('patient_id', $appointment->patient_id) === (string)$patient->id ? 'selected' : '' }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->medical_record_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Doctor -->
                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor</label>
                            <select id="doctor_id" name="doctor_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Doctor --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ (string)old('doctor_id', $appointment->doctor_id) === (string)$doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }}
                                        @if(optional($doctor->doctorProfile->specialty)->name)
                                            ({{ $doctor->doctorProfile->specialty->name }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date & Time -->
                        <div>
                            <label for="appointment_date" class="block text-sm font-medium text-gray-700">Appointment Date & Time</label>

                            @php
                                // IMPORTANT: datetime-local requires "YYYY-MM-DDTHH:MM"
                                $dtValue = old(
                                    'appointment_date',
                                    optional($appointment->appointment_date)->format('Y-m-d\TH:i')
                                );
                            @endphp

                            <input id="appointment_date" type="datetime-local" name="appointment_date"
                                   value="{{ $dtValue }}"
                                   step="900"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                            <p class="text-xs text-gray-500 mt-1 italic">
                                Appointments are scheduled in 15-minute blocks.
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @php($st = old('status', $appointment->status))
                                <option value="scheduled" {{ $st === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ $st === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $st === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no-show" {{ $st === 'no-show' ? 'selected' : '' }}>No-Show</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Reason / notes...">{{ old('notes', $appointment->notes) }}</textarea>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('appointments.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md font-semibold text-sm shadow hover:bg-gray-200">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm shadow hover:bg-indigo-700
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
