<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Patient Management
            </h2>

            <a href="{{ route('patients.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm shadow
                      hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                + Add Patient
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
                <div class="p-6 border-b border-gray-200">
                    <form method="GET" action="{{ route('patients.index') }}">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name, MRN, or phone..."
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </form>
                </div>

                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">MRN</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Full Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($patients as $patient)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $patient->medical_record_number }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ ucfirst($patient->gender) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $patient->phone }}</td>

                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    <a href="{{ route('patients.edit', $patient) }}"
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs text-gray-700 bg-white hover:bg-gray-50">
                                        Edit
                                    </a>

                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this patient?');">
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
                                    No patients found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    @if(method_exists($patients, 'links'))
                        <div class="mt-4">
                            {{ $patients->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
