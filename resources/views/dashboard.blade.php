<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clinical Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Total Patients -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Patients</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_patients']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Appointments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Today's Appts</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['today_appointments'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ER Occupancy -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-red-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-full text-red-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">ER Occupancy</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['er_occupancy'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inpatients -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-purple-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Inpatients</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['inpatients_count'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-2">Welcome to SehaLink Edara</h3>
                    <p class="text-gray-600">The system is initialized. You are currently viewing the clinical overview. Use the navigation to manage patients and appointments.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>