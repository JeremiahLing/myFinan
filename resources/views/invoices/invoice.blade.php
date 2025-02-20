<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Invoice Management') }}
        </h2>
    </x-slot>

    <div class="bg-purple-900 text-white flex flex-col items-center py-10 animate__animated animate__fadeInUp" style="background-color: #CCAAFFaa;">    
        <div class="button-container mb-6">
            <a href="{{ route('invoices.create') }}" class="history-btn text-xl mb-4">
                Create New Invoice
            </a>
        </div>

        <div class="container mb-6 mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg animate__animated animate__fadeInUp" style="background-color: rgba(255, 255, 255, 0.3);">
            <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Invoice List</h1>

            @if($invoices->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No invoices found.</p>
            @else
                <!-- Table -->
                <table class="table-auto w-full text-left" style="background-color: #FFFFFF;">
                    <thead class="bg-gray-100 dark:bg-gray-700 animate__animated animate__fadeInUp">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Invoice #</th>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Client</th>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Date</th>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Due Date</th>
                            <th class="px-4 py-2 text-gray-800 dark:text-white">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="window.location='{{ route('invoices.show', $invoice->id) }}'">
                                <td class="border border-gray-300 dark:border-gray-700 text-center">
                                    <input type="checkbox" class="row-select" data-id="{{ $invoice->id }}" onclick="event.stopPropagation();">
                                </td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $invoice->invoice_number }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $invoice->client_name }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $invoice->invoice_date }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ $invoice->due_date }}</td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">RM{{ number_format($invoice->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button id="delete-selected" class="bg-red-600 text-white px-4 py-2 rounded-lg mt-4 hover:bg-red-700 focus:outline-none">
                    Delete Selected Invoice
                </button>

                <div class="mt-6">
                    <p class="text-gray-600 dark:text-gray-400">Total Invoices: {{ $invoices->count() }}</p>
                </div>
            @endif

            <!-- Pagination -->
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        </div>

        <style>
            table tbody tr {
                transition: background-color 0.3s ease;
            }

            table tbody tr:hover {
                background-color: #f0f0f0;
                cursor: pointer;
            }
        </style>

        <script>
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK',
                });
            @endif

            document.getElementById('select-all').addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.row-select');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            document.getElementById('delete-selected').addEventListener('click', function () {
                const selectedRows = Array.from(document.querySelectorAll('.row-select:checked'));
                if (selectedRows.length === 0) {
                    alert('No rows selected.');
                    return;
                }

                const ids = selectedRows.map(row => row.getAttribute('data-id'));

                if (confirm('Are you sure you want to delete the selected rows?')) {
                    fetch('/delete-selected-items', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ ids })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            selectedRows.forEach(row => row.closest('tr').remove());
                            alert('Selected rows deleted successfully.');
                        } else {
                            alert('An error occurred while deleting rows.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        </script>
    </div>

    <!--footer-->
    @include('components.footer')
</x-app-layout>
