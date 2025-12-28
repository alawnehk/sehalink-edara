<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Encounter #{{ $encounter->id }}
            </h2>

            <div class="flex items-center gap-2">
                <a href="{{ route('encounters.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md font-semibold text-sm shadow hover:bg-gray-200">
                    Back
                </a>

                @if($encounter->status === 'open')
                    <a href="{{ route('encounters.edit', $encounter) }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm shadow hover:bg-indigo-700
                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Edit
                    </a>

                    <form action="{{ route('encounters.close', $encounter) }}" method="POST" class="inline-block"
                          onsubmit="return confirm('Close this encounter?');">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md font-semibold text-sm shadow hover:bg-emerald-700
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Close
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">Patient</div>
                        <div class="text-lg font-semibold text-gray-900">
                            {{ $encounter->patient->first_name ?? '' }} {{ $encounter->patient->last_name ?? '' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            MRN: {{ $encounter->patient->medical_record_number ?? '-' }}
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-sm text-gray-500">Doctor</div>
                        <div class="text-lg font-semibold text-gray-900">
                            Dr. {{ $encounter->doctor->name ?? '' }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Created: {{ $encounter->created_at?->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </div>

                <div>
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold
                        {{ $encounter->status === 'open' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($encounter->status) }}
                    </span>

                    @if($encounter->appointment)
                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-800">
                            Linked Appointment #{{ $encounter->appointment->id }} — {{ $encounter->appointment->appointment_date?->format('Y-m-d H:i') }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-gray-700">Chief Complaint</div>
                        <div class="mt-1 text-gray-900">
                            {{ $encounter->chief_complaint }}
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-gray-700">HPI</div>
                        <div class="mt-1 text-gray-900 whitespace-pre-line">
                            {{ $encounter->hpi ?: '-' }}
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-gray-700">Vitals</div>
                        @php($v = $encounter->vitals ?? [])
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="rounded border p-3">
                                <div class="text-xs text-gray-500">BP</div>
                                <div class="font-semibold">{{ $v['bp'] ?? '-' }}</div>
                            </div>
                            <div class="rounded border p-3">
                                <div class="text-xs text-gray-500">HR</div>
                                <div class="font-semibold">{{ $v['hr'] ?? '-' }}</div>
                            </div>
                            <div class="rounded border p-3">
                                <div class="text-xs text-gray-500">Temp</div>
                                <div class="font-semibold">{{ $v['temp'] ?? '-' }}</div>
                            </div>
                            <div class="rounded border p-3">
                                <div class="text-xs text-gray-500">RR</div>
                                <div class="font-semibold">{{ $v['rr'] ?? '-' }}</div>
                            </div>
                            <div class="rounded border p-3">
                                <div class="text-xs text-gray-500">SpO2</div>
                                <div class="font-semibold">{{ $v['spo2'] ?? '-' }}</div>
                            </div>
                            <div class="rounded border p-3">
                                <div class="text-xs text-gray-500">Weight</div>
                                <div class="font-semibold">{{ $v['weight'] ?? '-' }}</div>
                            </div>
                            <div class="rounded border p-3">
                                <div class="text-xs text-gray-500">Height</div>
                                <div class="font-semibold">{{ $v['height'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-gray-700">Diagnosis</div>
                        <div class="mt-1 text-gray-900 whitespace-pre-line">
                            {{ $encounter->diagnosis ?: '-' }}
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-gray-700">Plan</div>
                        <div class="mt-1 text-gray-900 whitespace-pre-line">
                            {{ $encounter->plan ?: '-' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
