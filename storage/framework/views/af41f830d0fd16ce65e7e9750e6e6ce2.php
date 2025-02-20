<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Financial Report Management 📊')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-purple-900 text-white flex flex-col items-center py-10 animate__animated animate__fadeInUp" style="background-color: #CCAAFFaa;">
        <!-- Month Selection -->
        <form method="GET" action="<?php echo e(route('report')); ?>" class="mb-6 flex flex-row items-center gap-4 justify-center">
            <label for="month" class="text-gray-700 font-semibold">Select Month:</label>
            <input type="month" id="month" name="month" value="<?php echo e($selectedMonth); ?>" class="text-gray-800 p-2 rounded-lg border border-gray-300">
            <button type="submit"
                    class="save-btn bg-green-400 text-white py-3 px-6 rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition">
                Filter
                <img src="<?php echo e(asset('microscope.gif')); ?>" alt="Loading icon" class="ml-2" style="width: 24px; height: 24px;">
            </button>
        </form>


        <!-- Transaction Table -->
        <div class="container mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center mb-6 animate__animated animate__fadeInUp" style="background-color: rgba(255, 255, 255, 0.3);"> 
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Transactions of <?php echo e(\Carbon\Carbon::parse($selectedMonth)->format('F Y')); ?></h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-600 text-gray-800">
                        <th class="py-2">Name</th>
                        <th class="py-2">Description</th>
                        <th class="py-2">Amount</th>
                        <th class="py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-gray-700 text-gray-800">
                            <td class="py-2"><?php echo e($transaction->item_name); ?></td>
                            <td class="py-2"><?php echo e($transaction->description); ?></td>
                            <td class="py-2" style="color: <?php echo e($transaction->type === 'income' ? '#4CAF50' : '#F44336'); ?>;">
                                <?php echo e($transaction->type === 'income' ? '+' : '-'); ?> RM<?php echo e(number_format($transaction->amount, 2)); ?>

                            </td>
                            <td class="py-2"><?php echo e($transaction->date); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="py-2 text-center text-gray-800">No transactions available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Summary Section with Pie Chart and Balance -->
        <div class="p-3 flex space-x-8 mb-4">
            
            <!-- Pie Chart -->
            <div class="p-6 rounded-lg shadow-lg animate__animated animate__fadeInLeft" style="width: 270px; height: 256px; background-color: rgba(255, 0, 255, 0.2); display: flex; justify-content: center; align-items: center;">
                <canvas id="incomeExpenseChart"></canvas>
            </div>

            <div>
                <!-- Total Balance -->
                <div class="max-w-3xl w-auto p-6 rounded-lg shadow-lg w-48 h-48 flex flex-col items-center justify-center animate__animated animate__fadeInRight" style="background-color: rgba(255, 255, 255, 0.2);">
                    <p class="text-sm font-semibold text-gray-800">Total Balance</p>
                    <p class="text-3xl font-semibold text-gray-800">RM<?php echo e(number_format($totalBalance, 2)); ?></p>
                    <p class="<?php echo e($balancePercentage >= 0 ? 'text-green-600' : 'text-red-600'); ?> text-xl font-semibold">
                        <?php echo e($balancePercentage >= 0 ? '+' : ''); ?><?php echo e($balancePercentage); ?>%
                    </p>
                </div>

                <!--Inspect-->
                <button class="button-container animate__animated animate__fadeInRight">
                    <a 
                        href="<?php echo e(route('inspect')); ?>" 
                        class="mt-3 font-semibold text-gray-800 bg-indigo-600 py-3 px-6 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg hover:text-black" 
                        style="transform: translateY(-3px);">
                        Inspect Yearly Chart
                    </a>
                </button>
            </div>
        </div>
        
        <div class="p-4 button-container flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-6">
            <a href="<?php echo e(route('expense.history')); ?>" 
                class="history-btn bg-blue-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition">
                Expense History
            </a>
            <a href="<?php echo e(route('income.history')); ?>" 
                class="history-btn bg-blue-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition">
                Income History
            </a>
            <a href="<?php echo e(route('invoice')); ?>" 
                class="history-btn bg-blue-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition">
                Invoice
            </a>
        </div>

        <div class="p-4 flex flex-col items-center">
            <form method="POST" action="<?php echo e(route('sendMonthlyReport')); ?>" class="flex flex-row items-center gap-4 w-full justify-center">
                <?php echo csrf_field(); ?>
                <!-- Month Selection -->
                <div class="flex flex-col items-start">
                    <label for="month" class="text-gray-700 font-medium">Select Month:</label>
                    <input type="month" id="month" name="month" class="text-gray-800 p-2 rounded-md border border-gray-300" required>
                </div>

                <!-- Email Input -->
                <div class="flex flex-col items-start">
                    <label for="email" class="text-gray-700 font-medium">Recipient Email:</label>
                    <input type="email" id="email" name="email" placeholder="example@example.com" class="text-gray-800 p-2 rounded-md border border-gray-300" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="mt-6 save-btn p-2 rounded-md shadow-lg px-4 flex items-center">
                    Send Report
                    <img src="<?php echo e(asset('message.gif')); ?>" alt="Loading icon" class="ml-2" style="width: 16px; height: 16px;">
                </button>
            </form>

            <!-- Success Message -->
            <?php if(session('success')): ?>
                <div class="rounded-lg shadow-lg text-gray-800 font-semibold p-2 w-full text-center mt-4" style="background-color: rgba(255, 0, 255, 0.3);">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
        </div>   
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center mt-4 mb-4">
            <!-- Search Bar -->
            <div class="animate__animated animate__fadeInUp">
                <?php if(!$reports->isEmpty()): ?>
                    <label for="searchInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Search Send Report
                    </label>
                    <input
                        type="text"
                        id="searchInput"
                        placeholder="Search..."
                        class="w-fill px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-gray-900"
                    >
                <?php endif; ?>
            </div>
        </div>

        <div class="container mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center mb-6" style="background-color: #FFFFFFaa;">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-200">Report Send History</h2>
    
            <table class="w-full border-collapse rounded-lg overflow-hidden shadow-md">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr class="text-gray-800 dark:text-gray-300">
                        <th class="py-3 px-4 text-center">
                            <input type="checkbox" id="select-all" class="cursor-pointer">
                        </th>
                        <th class="py-3 px-4 text-left">Recipient Email</th>
                        <th class="py-3 px-4 text-left">Selected Month</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Sent At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                            <td class="py-3 px-4 text-center">
                                <input type="checkbox" class="row-select cursor-pointer" data-id="<?php echo e($report->id); ?>">
                            </td>
                            <td class="py-3 px-4 text-gray-900 dark:text-gray-300"><?php echo e($report->email); ?></td>
                            <td class="py-3 px-4 text-gray-900 dark:text-gray-300"><?php echo e(\Carbon\Carbon::parse($report->month)->format('F Y')); ?></td>
                            <td class="py-3 px-4 text-green-600 font-semibold"><?php echo e(ucfirst($report->status)); ?></td>
                            <td class="py-3 px-4 text-gray-900 dark:text-gray-300"><?php echo e($report->created_at->format('d M Y, H:i A')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <button id="delete-selected" class="mt-6 bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                Delete Selected Row
            </button>
        </div>
    </div>

    <!-- Include Chart.js and Chart Configuration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const totalIncome = <?php echo e($totalIncome); ?>;
        const totalExpense = <?php echo e($totalExpense); ?>;

        const incomeExpenseData = {
            labels: ['Incomes', 'Expenses'],
            datasets: [{
                data: [totalIncome, totalExpense],
                backgroundColor: ['#4CAF50', '#F44336'],
                hoverBackgroundColor: ['#45A049', '#D32F2F']
            }]
        };

        const noRecordPlugin = {
            id: 'noRecordPlugin',
            beforeDraw: (chart) => {
                const { data } = chart;
                const hasData = data.datasets[0].data.some((value) => value > 0);

                if (!hasData) {
                    const ctx = chart.ctx;
                    const width = chart.width;
                    const height = chart.height;

                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#ffffff';
                    ctx.font = '24px sans-serif';
                    ctx.fillText('No Record', width / 2, height / 2);
                    ctx.restore();
                }
            }
        };

        const config = {
            type: 'doughnut',
            data: incomeExpenseData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: { color: '#000000' }
                    },
                },
                cutout: '60%',
            },
            plugins: [noRecordPlugin]
        };

        const incomeExpenseChart = new Chart(
            document.getElementById('incomeExpenseChart').getContext('2d'),
            config
        );

            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?php echo e(session('success')); ?>',
                    confirmButtonText: 'OK',
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?php echo e(session('error')); ?>',
                    confirmButtonText: 'OK',
                });
            <?php endif; ?>

        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('tbody tr');

            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const query = searchInput.value.toLowerCase();

                    tableRows.forEach(row => {
                        const recipientEmail = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const selectedMonth = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                        // Check if any column contains the search query
                        if (recipientEmail.includes(query) || selectedMonth.includes(query) || status.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });

            document.getElementById('select-all').addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.row-select');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            document.getElementById('delete-selected').addEventListener('click', function () {
                const selectedRows = Array.from(document.querySelectorAll('.row-select:checked'));
                if (selectedRows.length === 0) {
                    alert('No column selected.');
                    return;
                }

                const ids = selectedRows.map(row => row.getAttribute('data-id'));

                if (confirm('Are you sure you want to delete the selected rows?')) {
                    fetch('/reports/delete-selected', {
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

    <!--Footer-->
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\User\myFinan\resources\views/report.blade.php ENDPATH**/ ?>