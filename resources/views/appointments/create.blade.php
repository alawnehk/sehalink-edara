<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Schedule Smart Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 border border-gray-100">
                <form method="POST" action="{{ route('appointments.store') }}" id="appointmentForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Selection -->
                        <div>
                            <x-input-label for="patient_id" :value="__('Patient')" />
                            <select id="patient_id" name="patient_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">-- Select Patient --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->first_name }} {{ $patient->last_name }} ({{ $patient->medical_record_number }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        <!-- Doctor Selection -->
                        <div>
                            <x-input-label for="doctor_id" :value="__('Doctor')" />
                            <select id="doctor_id" name="doctor_id" onchange="updateAvailabilityHint()" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">-- Select Doctor --</option>
                                @foreach($doctors as $doctor)
                                    <option
                                        value="{{ $doctor->id }}"
                                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}
                                        data-availability="{{ json_encode($doctor->doctorAvailabilities) }}"
                                    >
                                        Dr. {{ $doctor->name }} ({{ $doctor->doctorProfile->specialty->name ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />

                            <!-- Availability Hint -->
                            <div id="availability-hint" class="mt-2 text-sm text-blue-600 font-medium hidden">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    <span id="hint-text"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div>
                            <x-input-label for="appointment_date" :value="__('Appointment Date & Time')" />

                            <!-- ✅ 15-minute slots: step="900" seconds -->
                            <x-text-input
                                id="appointment_date"
                                class="block mt-1 w-full"
                                type="datetime-local"
                                name="appointment_date"
                                step="900"
                                :value="old('appointment_date')"
                                required
                            />

                            <p class="text-xs text-gray-500 mt-1 italic">
                                Note: Appointments are scheduled in 15-minute blocks (e.g., 10:00, 10:15, 10:30, 10:45).
                            </p>

                            <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Initial Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no-show" {{ old('status') == 'no-show' ? 'selected' : '' }}>No-Show</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <x-input-label for="notes" :value="__('Reason for Visit / Notes')" />
                        <textarea id="notes" name="notes" rows="3"
                                  class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Briefly describe the symptoms or purpose...">{{ old('notes') }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-8 gap-4">
                        <a href="{{ route('appointments.index') }}" class="text-gray-600 hover:underline text-sm">Cancel</a>
                        <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                            {{ __('Confirm Appointment') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateAvailabilityHint() {
            const select = document.getElementById('doctor_id');
            const hintDiv = document.getElementById('availability-hint');
            const hintText = document.getElementById('hint-text');
            const selectedOption = select.options[select.selectedIndex];

            if (!selectedOption || !selectedOption.value) {
                hintDiv.classList.add('hidden');
                return;
            }

            const availabilities = JSON.parse(selectedOption.getAttribute('data-availability') || '[]');
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            if (availabilities.length > 0) {
                const scheduleStrings = availabilities.map(a => {
                    const start = (a.start_time || '').toString().substring(0, 5);
                    const end = (a.end_time || '').toString().substring(0, 5);
                    return `${days[a.day_of_week]} (${start} - ${end})`;
                });

                hintText.innerText = "Available on: " + scheduleStrings.join(', ');
                hintDiv.classList.remove('hidden');
            } else {
                hintText.innerText = "No availability set for this doctor.";
                hintDiv.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', updateAvailabilityHint);
    </script>
</x-app-layout>
