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
            <?php echo e(__('Invoice Management')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-purple-900 text-white flex flex-col items-center py-10 animate__animated animate__fadeInUp" style="background-color: #CCAAFFaa;">    
        <div class="button-container mb-6">
            <a href="<?php echo e(route('invoices.create')); ?>" class="history-btn text-xl mb-4">
                Create New Invoice
            </a>
        </div>

        <div class="container mb-6 mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg animate__animated animate__fadeInUp" style="background-color: rgba(255, 255, 255, 0.3);">
            <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Invoice List</h1>

            <?php if($invoices->isEmpty()): ?>
                <p class="text-gray-600 dark:text-gray-400">No invoices found.</p>
            <?php else: ?>
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
                        <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-t border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                                onclick="window.location='<?php echo e(route('invoices.show', $invoice->id)); ?>'">
                                <td class="border border-gray-300 dark:border-gray-700 text-center">
                                    <input type="checkbox" class="row-select" data-id="<?php echo e($invoice->id); ?>" onclick="event.stopPropagation();">
                                </td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400"><?php echo e($invoice->invoice_number); ?></td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400"><?php echo e($invoice->client_name); ?></td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400"><?php echo e($invoice->invoice_date); ?></td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400"><?php echo e($invoice->due_date); ?></td>
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">RM<?php echo e(number_format($invoice->total, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                <button id="delete-selected" class="bg-red-600 text-white px-4 py-2 rounded-lg mt-4 hover:bg-red-700 focus:outline-none">
                    Delete Selected Invoice
                </button>

                <div class="mt-6">
                    <p class="text-gray-600 dark:text-gray-400">Total Invoices: <?php echo e($invoices->count()); ?></p>
                </div>
            <?php endif; ?>

            <!-- Pagination -->
            <div class="mt-4">
                <?php echo e($invoices->links()); ?>

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
<?php endif; ?>
<?php /**PATH C:\Users\User\myFinan\resources\views/invoices/invoice.blade.php ENDPATH**/ ?>