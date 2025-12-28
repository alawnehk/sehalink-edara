<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Encounter Details') }}
            </h2>
            <div class="flex gap-2">
                @if($encounter->status === 'open')
                    <a href="{{ route('encounters.edit', $encounter) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold">Edit</a>
                @endif
                <a href="{{ route('encounters.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm font-bold">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6 border-l-4 {{ $encounter->status === 'open' ? 'border-green-500' : 'border-gray-400' }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Patient</p>
                        <p class="text-lg font-bold text-gray-900">{{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</p>
                        <p class="text-sm text-gray-600">MRN: {{ $encounter->patient->medical_record_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Doctor</p>
                        <p class="text-lg font-bold text-gray-900">Dr. {{ $encounter->doctor->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Date & Status</p>
                        <p class="text-sm text-gray-900">{{ $encounter->created_at->format('M d, Y H:i') }}</p>
                        <span class="px-2 py-1 text-xs font-bold rounded-full {{ $encounter->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ strtoupper($encounter->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-md font-bold text-gray-700 border-b pb-2 mb-4">Clinical Findings</h3>
                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-bold text-gray-500">Chief Complaint</p>
                        <p class="text-gray-900">{{ $encounter->chief_complaint }}</p>
                    </div>
                    
                    @if($encounter->vitals)
                    <div>
                        <p class="text-sm font-bold text-gray-500 mb-2">Vitals</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                            @foreach($encounter->vitals as $key => $value)
                                <div class="bg-gray-50 p-2 rounded border text-center">
                                    <p class="text-[10px] uppercase text-gray-400 font-bold">{{ strtoupper($key) }}</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $value ?: '-' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm font-bold text-gray-500">HPI</p>
                        <p class="text-gray-900 whitespace-pre-line">{{ $encounter->hpi ?: 'No data recorded.' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500">Diagnosis</p>
                        <p class="text-gray-900 font-bold">{{ $encounter->diagnosis ?: 'Pending' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-500">Plan</p>
                        <p class="text-gray-900 whitespace-pre-line">{{ $encounter->plan ?: 'No data recorded.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>