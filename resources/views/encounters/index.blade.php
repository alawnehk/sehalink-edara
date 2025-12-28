<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Encounters
            </h2>

            <a href="{{ route('encounters.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-sm shadow
                      hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                + New Encounter
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
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($encounters as $encounter)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $encounter->patient->first_name ?? '' }} {{ $encounter->patient->last_name ?? '' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    Dr. {{ $encounter->doctor->name ?? '' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $encounter->created_at?->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold
                                        {{ $encounter->status === 'open' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($encounter->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    <a href="{{ route('encounters.show', $encounter) }}"
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs text-gray-700 bg-white hover:bg-gray-50">
                                        View
                                    </a>

                                    <a href="{{ route('encounters.edit', $encounter) }}"
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs text-gray-700 bg-white hover:bg-gray-50">
                                        Edit
                                    </a>

                                    @if($encounter->status === 'open')
                                        <form action="{{ route('encounters.close', $encounter) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Close this encounter?');">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1 rounded-md text-xs text-white bg-emerald-600 hover:bg-emerald-700">
                                                Close
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                    No encounters found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    @if(method_exists($encounters, 'links'))
                        <div class="mt-4">
                            {{ $encounters->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
