<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Encounter #{{ $encounter->id }}
            </h2>

            <a href="{{ route('encounters.show', $encounter) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md font-semibold text-sm shadow hover:bg-gray-200">
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

                <form method="POST" action="{{ route('encounters.update', $encounter) }}">
                    @csrf
                    @method('PUT')

                    @php($v = old('vitals', $encounter->vitals ?? []))

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <div class="text-sm text-gray-500">Patient</div>
                            <div class="font-semibold text-gray-900">
                                {{ $encounter->patient->first_name ?? '' }} {{ $encounter->patient->last_name ?? '' }}
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Chief Complaint</label>
                            <input name="chief_complaint" type="text" required
                                   value="{{ old('chief_complaint', $encounter->chief_complaint) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">HPI</label>
                            <textarea name="hpi" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('hpi', $encounter->hpi) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <div class="border rounded-md p-4">
                                <h3 class="font-semibold text-gray-800 mb-3">Vitals</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-700">BP</label>
                                        <input type="text" name="vitals[bp]" value="{{ $v['bp'] ?? '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700">HR</label>
                                        <input type="number" name="vitals[hr]" value="{{ $v['hr'] ?? '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700">Temp</label>
                                        <input type="number" step="0.1" name="vitals[temp]" value="{{ $v['temp'] ?? '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700">RR</label>
                                        <input type="number" name="vitals[rr]" value="{{ $v['rr'] ?? '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700">SpO2</label>
                                        <input type="number" name="vitals[spo2]" value="{{ $v['spo2'] ?? '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700">Weight</label>
                                        <input type="number" step="0.1" name="vitals[weight]" value="{{ $v['weight'] ?? '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700">Height</label>
                                        <input type="number" step="0.1" name="vitals[height]" value="{{ $v['height'] ?? '' }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Diagnosis</label>
                            <textarea name="diagnosis" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('diagnosis', $encounter->diagnosis) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Plan</label>
                            <textarea name="plan" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('plan', $encounter->plan) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('encounters.show', $encounter) }}"
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
