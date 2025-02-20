<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Expense History') }}
        </h2>
    </x-slot>

    <!-- Main Content Container -->
    <div class="min-h-screen py-10 w-full flex justify-center" style="background-color: #CCAAFFaa;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Text Container -->
            <div class="overflow-hidden shadow-sm sm:rounded-lg mb-6 max-w-md mx-auto p-6 text-center animate__animated animate__fadeInDown" style="background-color: #FFFFFF33;">
                @if (Auth::check())
                    <p>Welcome to the Expense History side!</p>
                @else
                    <p>Please log in to see your profile.</p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center mb-4">
                <!-- Search Bar -->
                <div class="animate__animated animate__fadeInUp">
                    @if(!$expenses->isEmpty())
                        <label for="searchInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Search Expenses
                        </label>
                        <input
                            type="text"
                            id="searchInput"
                            placeholder="Search..."
                            class="w-1/2 md:w-2/3 lg:w-1/2 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                        >
                    @endif
                </div>

                <!-- Month Selector -->
                <div class="animate__animated animate__fadeInUp">
                    <form method="GET" action="{{ route('expense.history') }}" class="w-full md:w-2/3 lg:w-1/2">
                        <label for="monthSelector" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Month
                        </label>
                        <select
                            name="month"
                            id="monthSelector"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                            onchange="this.form.submit()"
                        >
                            <option value="">All</option>
                            @foreach($months as $monthValue => $monthName)
                                <option value="{{ $monthValue }}" {{ $selectedMonth == $monthValue ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <!-- Expense Table -->
            <div class="p-6 form-container mx-auto rounded-lg shadow-lg animate__animated animate__fadeInUp" style="background-color: #FFFFFF77;">
                @if($expenses->isEmpty())
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">No expense history available.</p>
                @else
                    <table class="min-w-full table-auto border-collapse border border-gray-200 dark:border-gray-700" style="background-color: #FFFFFFaa;">
                            <thead>
                                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                    <th class="border border-gray-300 dark:border-gray-700 px-4 py-2">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Item ID</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Item Name</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Quantity</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Description</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Time</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Date</th>
                                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">Amount (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                    <tr class="text-gray-900 dark:text-gray-300" data-id="{{ $expense->item_id }}">
                                        <td class="border border-gray-300 dark:border-gray-700 text-center">
                                            <input type="checkbox" class="row-select" data-id="{{ $expense->id }}" onclick="event.stopPropagation();">
                                        </td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->item_id }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->item_name }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->quantity }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600">{{ $expense->description }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->time }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->date }}</td>
                                        <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-600 text-center">{{ $expense->amount }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-gray-500 py-4">
                                            No expense history available for the selected month.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                    </table>

                    <button id="delete-selected" class="bg-red-600 text-white px-4 py-2 rounded-lg mt-4 hover:bg-red-700 focus:outline-none">
                        Delete Selected Expense
                    </button>
                @endif
            </div>

            <!-- Back to Expense Button -->
            <div class="p-4 button-container">
                <a href="{{ url()->previous() }}">
                    <button type="button" class="cancel-btn shadow-lg">Back</button>
                </a>         
            </div>

            <!-- Edit Modal -->
            <div id="editExpenseModal" class="fixed z-10 inset-0 overflow-y-auto hidden animate__animated animate__fadeInUp" style="background-color: rgba(0, 0, 0, 0.5);">
                <div class="flex items-center justify-center min-h-screen px-4 py-12">
                    <div class="modal-content bg-white dark:bg-gray-800 shadow-lg rounded-lg max-w-lg w-full p-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Edit Expense Item</h2>
                        <form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('expense.update') }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="item_id" id="editItemId">

                            <div class="mb-4">
                                <label for="editItemName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name</label>
                                <input type="text" name="item_name" id="editItemName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            </div>

                            <div class="mb-4">
                                <label for="editQuantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                                <input type="number" name="quantity" id="editQuantity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            </div>

                            <div class="mb-4">
                                <label for="editDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" id="editDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"></textarea>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label for="editTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Time (HH:MM:SS)</label>
                                    <div class="time-container">
                                        <input type="time" name="time" id="editTime" step="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                </div>
                                <div>
                                    <label for="editDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                                    <div class="date-container">
                                        <input type="date" name="date" id="editDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    </div>
                                </div>
                                <div>
                                    <label for="editAmount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (RM)</label>
                                    <input type="number" name="amount" id="editAmount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                </div>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md shadow hover:bg-gray-700 hover:text-white">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-green-300 hover:text-black">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterByMonth() {
            const selectedMonth = document.getElementById('monthSelector').value;
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const dateCell = row.querySelector('td:nth-child(7)');
                if (selectedMonth === "" || (dateCell && dateCell.textContent.includes(`-${selectedMonth}-`))) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

            document.getElementById('select-all').addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.row-select');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            document.getElementById('delete-selected').addEventListener('click', function () {
                const selectedRows = Array.from(document.querySelectorAll('.row-select:checked'));
                if (selectedRows.length === 0) {
                    alert('No expenses selected.');
                    return;
                }

                const ids = selectedRows.map(row => row.getAttribute('data-id'));

                if (confirm('Are you sure you want to delete the selected rows?')) {
                    fetch('/expenses/delete-selected', {
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

        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('editExpenseModal');
            const cancelEdit = document.getElementById('cancelEdit');
            const modalContent = modal.querySelector('.modal-content');
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');
            const editForm = document.getElementById('editForm');

            editForm.addEventListener('submit', (event) => {
                event.preventDefault();

                const formData = new FormData(editForm);

                fetch(editForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert(data.message || 'Expense item updated successfully.');
                            modal.classList.add('hidden'); // Close modal

                            const updatedRow = document.querySelector(`tbody tr[data-id="${formData.get('item_id')}"]`);
                            if (updatedRow) {
                                updatedRow.querySelector('td:nth-child(3)').textContent = formData.get('item_name');
                                updatedRow.querySelector('td:nth-child(4)').textContent = formData.get('quantity');
                                updatedRow.querySelector('td:nth-child(5)').textContent = formData.get('description');
                                updatedRow.querySelector('td:nth-child(6)').textContent = formData.get('time');
                                updatedRow.querySelector('td:nth-child(7)').textContent = formData.get('date');
                                updatedRow.querySelector('td:nth-child(8)').textContent = formData.get('amount');
                            }
                        } else {
                            alert(data.message || 'Failed to update Expense item.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            });

            // Open modal on row click
            document.querySelectorAll('tbody tr').forEach(row => {
                row.addEventListener('click', () => {
                    const itemId = row.querySelector('td:nth-child(2)').textContent.trim();
                    const itemName = row.querySelector('td:nth-child(3)').textContent.trim();
                    const quantity = row.querySelector('td:nth-child(4)').textContent.trim();
                    const description = row.querySelector('td:nth-child(5)').textContent.trim();
                    const time = row.querySelector('td:nth-child(6)').textContent.trim();
                    const date = row.querySelector('td:nth-child(7)').textContent.trim();
                    const amount = row.querySelector('td:nth-child(8)').textContent.trim();
                    const formattedTime = time.includes(':') ? time : '00:00:00';

                    // Populate modal fields
                    document.getElementById('editItemId').value = itemId;
                    document.getElementById('editItemName').value = itemName;
                    document.getElementById('editQuantity').value = quantity;
                    document.getElementById('editDescription').value = description;
                    document.getElementById('editTime').value = formattedTime;
                    document.getElementById('editDate').value = date;
                    document.getElementById('editAmount').value = amount;

                    modal.classList.remove('hidden');
                });
            });

            // Close modal on Cancel button click
            cancelEdit.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            // Close modal when clicking outside the content
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const query = searchInput.value.toLowerCase();

                    tableRows.forEach(row => {
                        const itemName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                        const description = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                        const date = row.querySelector('td:nth-child(7)').textContent.toLowerCase();

                        if (itemName.includes(query) || description.includes(query) || date.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>

    <!-- Footer -->
    @include('components.footer')
</x-app-layout>
