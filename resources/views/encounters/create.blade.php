<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Encounter') }}
            </h2>

            <a href="{{ route('encounters.index') }}"
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

                <form method="POST" action="{{ route('encounters.store') }}">
                    @csrf

                    @php
                        // Prefill if coming from appointment
                        $prefAppt = $prefilled_appointment ?? null;

                        $prefPatientId = old('patient_id') ?? ($prefAppt->patient_id ?? null);
                        $prefDoctorId  = old('doctor_id')  ?? ($prefAppt->doctor_id ?? null);
                        $prefApptId    = old('appointment_id') ?? ($prefAppt->id ?? null);
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Patient -->
                        <div>
                            <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                            <select id="patient_id" name="patient_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Patient --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ (string)$prefPatientId === (string)$patient->id ? 'selected' : '' }}>
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
                                    <option value="{{ $doctor->id }}" {{ (string)$prefDoctorId === (string)$doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Appointment (optional) -->
                        <div class="md:col-span-2">
                            <label for="appointment_id" class="block text-sm font-medium text-gray-700">
                                Linked Appointment (optional)
                            </label>

                            @if(!empty($prefAppt))
                                <!-- if created from appointment, lock it -->
                                <input type="hidden" name="appointment_id" value="{{ $prefApptId }}">
                                <div class="mt-1 rounded-md border border-gray-200 bg-gray-50 p-3 text-sm text-gray-800">
                                    Linked to Appointment #{{ $prefAppt->id }} —
                                    {{ $prefAppt->appointment_date?->format('Y-m-d H:i') }}
                                </div>
                            @else
                                <select id="appointment_id" name="appointment_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- None --</option>
                                    @foreach($appointments as $appt)
                                        <option value="{{ $appt->id }}" {{ (string)$prefApptId === (string)$appt->id ? 'selected' : '' }}>
                                            #{{ $appt->id }} — {{ $appt->appointment_date?->format('Y-m-d H:i') }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">
                                    Only appointments without an encounter are listed here.
                                </p>
                            @endif
                        </div>

                        <!-- Chief Complaint -->
                        <div class="md:col-span-2">
                            <label for="chief_complaint" class="block text-sm font-medium text-gray-700">Chief Complaint</label>
                            <input id="chief_complaint" name="chief_complaint" type="text" required
                                   value="{{ old('chief_complaint') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., chest pain, fever, cough...">
                        </div>

                        <!-- HPI -->
                        <div class="md:col-span-2">
                            <label for="hpi" class="block text-sm font-medium text-gray-700">HPI (optional)</label>
                            <textarea id="hpi" name="hpi" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="History of present illness...">{{ old('hpi') }}</textarea>
                        </div>

                        <!-- Vitals -->
                        <div class="md:col-span-2">
                            <div class="border rounded-md p-4">
                                <h3 class="font-semibold text-gray-800 mb-3">Vitals (optional)</h3>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-700">BP</label>
                                        <input type="text" name="vitals[bp]" value="{{ old('vitals.bp') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="120/80">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">HR</label>
                                        <input type="number" name="vitals[hr]" value="{{ old('vitals.hr') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="bpm">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Temp</label>
                                        <input type="number" step="0.1" name="vitals[temp]" value="{{ old('vitals.temp') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="°C">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">RR</label>
                                        <input type="number" name="vitals[rr]" value="{{ old('vitals.rr') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="/min">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">SpO2</label>
                                        <input type="number" name="vitals[spo2]" value="{{ old('vitals.spo2') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="%">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Weight</label>
                                        <input type="number" step="0.1" name="vitals[weight]" value="{{ old('vitals.weight') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="kg">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700">Height</label>
                                        <input type="number" step="0.1" name="vitals[height]" value="{{ old('vitals.height') }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                               placeholder="cm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Diagnosis -->
                        <div class="md:col-span-2">
                            <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnosis (optional)</label>
                            <textarea id="diagnosis" name="diagnosis" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Working diagnosis...">{{ old('diagnosis') }}</textarea>
                        </div>

                        <!-- Plan -->
                        <div class="md:col-span-2">
                            <label for="plan" class="block text-sm font-medium text-gray-700">Plan (optional)</label>
                            <textarea id="plan" name="plan" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Plan / management...">{{ old('plan') }}</textarea>
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('encounters.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md font-semibold text-sm shadow hover:bg-gray-200">
                            Cancel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm shadow
                                       hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Encounter
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
