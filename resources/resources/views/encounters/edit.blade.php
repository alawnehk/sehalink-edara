<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Encounter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border border-gray-100">
                <form method="POST" action="{{ route('encounters.update', $encounter) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <x-input-label for="patient_id" :value="__('Patient')" />
                            <select name="patient_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ $encounter->patient_id == $patient->id ? 'selected' : '' }}>{{ $patient->first_name }} {{ $patient->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="doctor_id" :value="__('Doctor')" />
                            <select name="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $encounter->doctor_id == $doctor->id ? 'selected' : '' }}>Dr. {{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="chief_complaint" :value="__('Chief Complaint')" />
                            <x-text-input name="chief_complaint" type="text" class="mt-1 block w-full" :value="$encounter->chief_complaint" required />
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Vitals</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                            @php $v = $encounter->vitals; @endphp
                            <div><x-input-label value="BP" /><x-text-input name="vitals[bp]" type="text" class="mt-1 block w-full text-sm" :value="$v['bp'] ?? ''" /></div>
                            <div><x-input-label value="HR" /><x-text-input name="vitals[hr]" type="text" class="mt-1 block w-full text-sm" :value="$v['hr'] ?? ''" /></div>
                            <div><x-input-label value="Temp" /><x-text-input name="vitals[temp]" type="text" class="mt-1 block w-full text-sm" :value="$v['temp'] ?? ''" /></div>
                            <div><x-input-label value="RR" /><x-text-input name="vitals[rr]" type="text" class="mt-1 block w-full text-sm" :value="$v['rr'] ?? ''" /></div>
                            <div><x-input-label value="SpO2" /><x-text-input name="vitals[spo2]" type="text" class="mt-1 block w-full text-sm" :value="$v['spo2'] ?? ''" /></div>
                            <div><x-input-label value="Weight" /><x-text-input name="vitals[weight]" type="text" class="mt-1 block w-full text-sm" :value="$v['weight'] ?? ''" /></div>
                            <div><x-input-label value="Height" /><x-text-input name="vitals[height]" type="text" class="mt-1 block w-full text-sm" :value="$v['height'] ?? ''" /></div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="hpi" :value="__('HPI')" />
                            <textarea name="hpi" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ $encounter->hpi }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="diagnosis" :value="__('Diagnosis')" />
                            <textarea name="diagnosis" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ $encounter->diagnosis }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="plan" :value="__('Plan')" />
                            <textarea name="plan" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ $encounter->plan }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 gap-4">
                        <a href="{{ route('encounters.show', $encounter) }}" class="text-gray-600 hover:underline">Cancel</a>
                        <x-primary-button class="bg-indigo-600">
                            {{ __('Update Encounter') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>