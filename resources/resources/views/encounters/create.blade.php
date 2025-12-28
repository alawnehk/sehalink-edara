<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Start New Encounter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border border-gray-100">
                <form method="POST" action="{{ route('encounters.store') }}">
                    @csrf

                    @if(isset($prefilled_appointment))
                        <input type="hidden" name="appointment_id" value="{{ $prefilled_appointment->id }}">
                        <input type="hidden" name="patient_id" value="{{ $prefilled_appointment->patient_id }}">
                        <input type="hidden" name="doctor_id" value="{{ $prefilled_appointment->doctor_id }}">
                        
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-blue-800 font-bold">Linked to Appointment:</p>
                                <p class="text-sm text-blue-600">Patient: {{ $prefilled_appointment->patient->first_name }} | Doctor: Dr. {{ $prefilled_appointment->doctor->name }}</p>
                            </div>
                            <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded">Auto-filled</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        @if(!isset($prefilled_appointment))
                        <div>
                            <x-input-label for="patient_id" :value="__('Patient')" />
                            <select name="patient_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select Patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->medical_record_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="doctor_id" :value="__('Doctor')" />
                            <select name="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="col-span-2">
                            <x-input-label for="chief_complaint" :value="__('Chief Complaint')" />
                            <x-text-input name="chief_complaint" type="text" class="mt-1 block w-full" required placeholder="Reason for visit..." />
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Vitals</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                            <div>
                                <x-input-label value="BP (mmHg)" />
                                <x-text-input name="vitals[bp]" type="text" class="mt-1 block w-full text-sm" placeholder="120/80" />
                            </div>
                            <div>
                                <x-input-label value="HR (bpm)" />
                                <x-text-input name="vitals[hr]" type="text" class="mt-1 block w-full text-sm" placeholder="72" />
                            </div>
                            <div>
                                <x-input-label value="Temp (°C)" />
                                <x-text-input name="vitals[temp]" type="text" class="mt-1 block w-full text-sm" placeholder="36.6" />
                            </div>
                            <div>
                                <x-input-label value="RR" />
                                <x-text-input name="vitals[rr]" type="text" class="mt-1 block w-full text-sm" placeholder="16" />
                            </div>
                            <div>
                                <x-input-label value="SpO2 (%)" />
                                <x-text-input name="vitals[spo2]" type="text" class="mt-1 block w-full text-sm" placeholder="98" />
                            </div>
                            <div>
                                <x-input-label value="Weight (kg)" />
                                <x-text-input name="vitals[weight]" type="text" class="mt-1 block w-full text-sm" placeholder="70" />
                            </div>
                            <div>
                                <x-input-label value="Height (cm)" />
                                <x-text-input name="vitals[height]" type="text" class="mt-1 block w-full text-sm" placeholder="175" />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="hpi" :value="__('History of Present Illness (HPI)')" />
                            <textarea name="hpi" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <x-input-label for="diagnosis" :value="__('Diagnosis')" />
                            <textarea name="diagnosis" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div>
                            <x-input-label for="plan" :value="__('Plan / Management')" />
                            <textarea name="plan" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 gap-4">
                        <a href="{{ route('encounters.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                        <x-primary-button class="bg-indigo-600">
                            {{ __('Save Encounter') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>