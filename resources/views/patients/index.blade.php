<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <h2 style="font-size:20px;font-weight:600;color:#1f2937;">
                Patient Management
            </h2>

            <!-- ADD PATIENT BUTTON (FORCED COLOR) -->
            <a href="{{ route('patients.create') }}"
               style="
                    display:inline-flex;
                    align-items:center;
                    padding:10px 16px;
                    border-radius:8px;
                    background:#4f46e5;
                    color:#ffffff;
                    font-weight:600;
                    font-size:14px;
                    text-decoration:none;
                    box-shadow:0 1px 2px rgba(0,0,0,0.15);
               "
               onmouseover="this.style.background='#4338ca'"
               onmouseout="this.style.background='#4f46e5'">
               + Add Patient
            </a>
        </div>
    </x-slot>

    <div style="padding:32px;">
        <div style="max-width:1100px;margin:auto;">

            @if (session('success'))
                <div style="
                    margin-bottom:16px;
                    padding:12px;
                    background:#ecfdf5;
                    color:#065f46;
                    border-radius:6px;
                    font-size:14px;
                ">
                    {{ session('success') }}
                </div>
            @endif

            <div style="background:#ffffff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,0.1);">

                <!-- SEARCH -->
                <div style="padding:16px;border-bottom:1px solid #e5e7eb;">
                    <form method="GET" action="{{ route('patients.index') }}">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name, MRN, or phone..."
                               style="
                                   width:100%;
                                   padding:10px;
                                   border:1px solid #d1d5db;
                                   border-radius:6px;
                                   font-size:14px;
                               ">
                    </form>
                </div>

                <!-- TABLE -->
                <div style="padding:16px;overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th style="padding:10px;text-align:left;font-size:12px;color:#6b7280;">MRN</th>
                                <th style="padding:10px;text-align:left;font-size:12px;color:#6b7280;">Full Name</th>
                                <th style="padding:10px;text-align:left;font-size:12px;color:#6b7280;">Gender</th>
                                <th style="padding:10px;text-align:left;font-size:12px;color:#6b7280;">Phone</th>
                                <th style="padding:10px;text-align:right;font-size:12px;color:#6b7280;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($patients as $patient)
                                <tr style="border-top:1px solid #e5e7eb;">
                                    <td style="padding:10px;font-size:14px;">
                                        {{ $patient->medical_record_number }}
                                    </td>
                                    <td style="padding:10px;font-size:14px;">
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </td>
                                    <td style="padding:10px;font-size:14px;">
                                        {{ ucfirst($patient->gender) }}
                                    </td>
                                    <td style="padding:10px;font-size:14px;">
                                        {{ $patient->phone }}
                                    </td>
                                    <td style="padding:10px;text-align:right;">
                                        <a href="{{ route('patients.edit', $patient) }}"
                                           style="padding:6px 10px;border:1px solid #d1d5db;border-radius:4px;font-size:12px;text-decoration:none;color:#374151;">
                                            Edit
                                        </a>

                                        <form action="{{ route('patients.destroy', $patient) }}"
                                              method="POST"
                                              style="display:inline-block;margin-left:6px;"
                                              onsubmit="return confirm('Delete this patient?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    style="padding:6px 10px;border:none;border-radius:4px;font-size:12px;background:#dc2626;color:#ffffff;">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding:20px;text-align:center;color:#6b7280;">
                                        No patients found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if(method_exists($patients, 'links'))
                        <div style="margin-top:16px;">
                            {{ $patients->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
