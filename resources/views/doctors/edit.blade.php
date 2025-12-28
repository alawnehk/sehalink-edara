<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Doctor: ') }} {{ $doctor->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form method="POST" action="{{ route('doctors.update', $doctor) }}">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Account Info -->
                        <div class="col-span-2 border-b pb-2">
                            <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                        </div>
                        
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $doctor->name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $doctor->email)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password (Leave blank to keep current)')" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                            <x-input-error class="mt-2" :messages="$errors->get('password')" />
                        </div>

                        <!-- Professional Profile -->
                        <div class="col-span-2 border-b pb-2 mt-4">
                            <h3 class="text-lg font-medium text-gray-900">Professional Profile</h3>
                        </div>

                        <div>
                            <x-input-label for="clinic_id" :value="__('Clinic')" />
                            <select id="clinic_id" name="clinic_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                @foreach($clinics as $clinic)
                                    <option value="{{ $clinic->id }}" {{ old('clinic_id', $doctor->doctorProfile->clinic_id) == $clinic->id ? 'selected' : '' }}>{{ $clinic->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('clinic_id')" />
                        </div>

                        <div>
                            <x-input-label for="specialty_id" :value="__('Specialty')" />
                            <select id="specialty_id" name="specialty_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}" {{ old('specialty_id', $doctor->doctorProfile->specialty_id) == $specialty->id ? 'selected' : '' }}>{{ $specialty->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('specialty_id')" />
                        </div>

                        <div>
                            <x-input-label for="license_number" :value="__('License Number')" />
                            <x-text-input id="license_number" name="license_number" type="text" class="mt-1 block w-full" :value="old('license_number', $doctor->doctorProfile->license_number)" />
                            <x-input-error class="mt-2" :messages="$errors->get('license_number')" />
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Contact Phone')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $doctor->doctorProfile->phone)" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div>
                            <x-input-label for="is_active" :value="__('Status')" />
                            <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="1" {{ old('is_active', $doctor->doctorProfile->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $doctor->doctorProfile->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('is_active')" />
                        </div>

                        <div class="col-span-2">
                            <x-input-label for="bio" :value="__('Biography / Notes')" />
                            <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', $doctor->doctorProfile->bio) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 gap-4">
                        <a href="{{ route('doctors.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                        <x-primary-button>{{ __('Update Doctor') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>