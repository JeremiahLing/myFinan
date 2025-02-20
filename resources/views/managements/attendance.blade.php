<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Staff Attendance') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-10" style="background-color: #CCAAFFaa">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Table Container -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 animate__animated animate__fadeInLeft" style="background-color: rgba(255, 255, 255, 0.5)">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Teammates</h3>
                @if($staff->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">No Staff Added Yet!?</p>
                @else
                    <div class="flex">
                        <!-- Fixed Columns -->
                        <div class="flex-none">
                            <table class="table-auto border-collapse border border-gray-300 text-sm w-auto">
                                <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="border border-gray-300 px-4 py-2">Staff ID</th>
                                        <th class="border border-gray-300 px-4 py-2">Staff Name</th>
                                        <th class="border border-gray-300 px-4 py-2">Joined Date</th>
                                        <th class="border border-gray-300 px-4 py-2">Month</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($staff as $member)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="border border-gray-300 px-4 py-2">{{ $member->staff_id }}</td>
                                            <td class="border border-gray-300 px-4 py-2">{{ $member->name }}</td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                {{ \Carbon\Carbon::parse($member->joined_date ?? $member->created_at)->format('d-m-Y') }}
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">{{ now()->format('F Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Scrollable Date Columns -->
                        <div class="flex-1 overflow-x-auto">
                            <table class="table-auto border-collapse border border-gray-300 text-sm w-full">
                                <thead class="sticky top-0 bg-gray-200 dark:bg-gray-700">
                                    <tr>
                                        @for ($day = 1; $day <= 31; $day++)
                                            @php
                                                $isToday = $day == now()->day;
                                            @endphp
                                            <th id="{{ $isToday ? 'today-column' : '' }}" 
                                                class="{{ $isToday ? 'bg-yellow-200' : '' }} border border-gray-300 px-2 py-2 text-center">
                                                {{ $day }}
                                            </th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($staff as $member)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            @for ($day = 1; $day <= 31; $day++)
                                                @php
                                                    $date = now()->format('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
                                                    $isPresent = $attendance->first(fn($record) => $record->staff_id == $member->id && $record->date === $date);
                                                @endphp
                                                <td class="border border-gray-300 px-2 py-2 text-center">
                                                    <button
                                                        class="attendance-button"
                                                        data-staff-id="{{ $member->id }}"
                                                        data-date="{{ $date }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $isPresent ? 'text-green-500' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-8V6a1 1 0 112 0v4h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Scroll to today's column
            const todayColumn = document.getElementById('today-column');
            if (todayColumn) {
                todayColumn.scrollIntoView({ behavior: 'smooth', inline: 'center' });
            }

            // Add event listeners for attendance buttons
            document.querySelectorAll('.attendance-button').forEach(button => {
                button.addEventListener('click', event => {
                    event.preventDefault();

                    const staffId = button.getAttribute('data-staff-id');
                    const date = button.getAttribute('data-date');
                    const svgIcon = button.querySelector('svg');

                    fetch('/managements/attendance/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ staff_id: staffId, date: date })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Toggle the icon color
                                if (svgIcon.classList.contains('text-gray-300')) {
                                    svgIcon.classList.remove('text-gray-300');
                                    svgIcon.classList.add('text-green-500');
                                } else {
                                    svgIcon.classList.remove('text-green-500');
                                    svgIcon.classList.add('text-gray-300');
                                }
                            } else {
                                alert(data.message || 'Failed to update attendance.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                });
            });
        });
    </script>

    <!-- Footer -->
    @include('components.footer')
</x-app-layout>