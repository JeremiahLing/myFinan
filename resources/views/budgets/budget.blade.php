<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Budget Management') }}
        </h2>
    </x-slot>

    <!-- Centering the main content -->
    <div class="py-12 w-full flex items-center justify-center" style="background-color: #CCAAFFaa;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Text Container with limited width -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 max-w-md mx-auto p-6 text-center animate__animated animate__fadeInDown" style="background-color: #FFFFFF33;">
                @if (Auth::check())
                    <p>Welcome to the Budget Management side!</p>
                @else
                    <p>Please log in to see your profile.</p>
                @endif
            </div>

            <!-- Form Container centered and styled -->
            <div class="form-container mx-auto animate__animated animate__fadeInUp">
                <form action="{{ route('budget.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div>
                            <label for="item_id">Item ID</label>
                            <input type="text" name="item_id" id="item_id" value="{{ $nextItemId }}" readonly>
                        </div>
                        <div>
                            <label for="category">Category</label>
                            <select name="category" id="category" required>
                                <option value="" disabled selected>Select a category</option>
                                <option value="Food">Food</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Housing">Salary</option>
                                <option value="Utilities">Utilities</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Enter description here..."></textarea>

                    <div class="form-row">
                        <div>
                            <label for="time">Time</label>
                            <div class="time-container">
                                <input type="time" name="time" id="time" placeholder="HH: MM: SS">
                            </div>
                        </div>
                        <div>
                            <label for="date">Date</label>
                            <div class="date-container">
                                <input type="date" name="date" id="date" placeholder="YYYY-MM-DD">
                            </div>
                        </div>
                        <div>
                            <label for="amount">Amount (RM)</label>
                            <input type="text" name="amount" id="amount" placeholder="XXX">
                        </div>
                    </div>

                    <div class="p-4 button-container flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-6">
                        <!-- Cancel Button -->
                        <button type="reset" 
                                class="cancel-btn bg-red-300 text-white py-3 px-6 rounded-lg shadow-md hover:bg-red-600 hover:shadow-lg transition">
                            Cancel
                        </button>

                        <!-- History Button -->
                        <a href="{{ route('budget.history') }}" 
                        class="history-btn bg-blue-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition">
                            History
                        </a>

                        <!-- Save Button -->
                        <button type="submit" 
                                class="save-btn bg-green-400 text-white py-3 px-6 rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition">
                            Save
                        </button>
                    </div>
                </form>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <style>
                /* General styling for the form */
                .form-container {
                    background-color: rgba(255, 255, 255, 0.9);
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    max-width: 500px;
                    width: 100%;
                }

                .form-container input[type="text"],
                .form-container textarea {
                    width: 100%;
                    padding: 10px;
                    margin: 5px 0 15px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size: 16px;
                }

                .form-container textarea {
                    resize: none;
                    height: 80px;
                }

                .form-container label {
                    font-weight: bold;
                    margin-bottom: 5px;
                    display: block;
                }

                .form-row {
                    display: flex;
                    justify-content: space-between;
                    gap: 10px;
                }

                /*date column*/
                .date-container {
                    margin-top: 10px; /* Moves the arrangement downward */
                }

                input[type="date"] {
                    border-radius: 10px;
                    padding: 10px 12px;
                    font-size: 16px;
                    border: 1px solid #ccc;
                    transition: border-color 0.3s ease;
                }

                input[type="date"]:focus {
                    border-color: #4A90E2;
                    outline: none;
                }

                /*time column*/
                .time-container {
                    margin-top: 10px;
                }

                input[type="time"] {
                    border-radius: 10px;
                    padding: 10px 12px;
                    font-size: 16px;
                    border: 1px solid #ccc;
                    transition: border-color 0.3s ease;
                }

                input[type="time"]:focus {
                    border-color: #4A90E2;
                    outline: none;
                }
            </style>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

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
    </script>
</x-app-layout>
