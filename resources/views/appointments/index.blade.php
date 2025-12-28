<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Appointment Management
            </h2>

            <a href="{{ route('appointments.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm shadow
                      hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                + Add Appointment
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($appointments as $appointment)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $appointment->patient->first_name ?? '' }} {{ $appointment->patient->last_name ?? '' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $appointment->doctor->name ?? '' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d H:i') }}
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    @php($status = $appointment->status)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold
                                        {{ $status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $status === 'no-show' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    ">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    <a href="{{ route('appointments.edit', $appointment) }}"
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs text-gray-700 bg-white hover:bg-gray-50">
                                        Edit
                                    </a>

                                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1 rounded-md text-xs text-white bg-red-600 hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                    No appointments found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    @if(method_exists($appointments, 'links'))
                        <div class="mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
