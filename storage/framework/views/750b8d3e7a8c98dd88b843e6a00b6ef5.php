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
            <?php echo e(__('Budget Management')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <!-- Centering the main content -->
    <div class="py-12 w-full flex items-center justify-center" style="background-color: #CCAAFFaa;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Text Container with limited width -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 max-w-md mx-auto p-6 text-center animate__animated animate__fadeInDown" style="background-color: #FFFFFF33;">
                <?php if(Auth::check()): ?>
                    <p>Welcome to the Budget Management side!</p>
                <?php else: ?>
                    <p>Please log in to see your profile.</p>
                <?php endif; ?>
            </div>

            <!-- Form Container centered and styled -->
            <div class="form-container mx-auto animate__animated animate__fadeInUp">
                <form action="<?php echo e(route('budget.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-row">
                        <div>
                            <label for="item_id">Item ID</label>
                            <input type="text" name="item_id" id="item_id" value="<?php echo e($nextItemId); ?>" readonly>
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
                        <a href="<?php echo e(route('budget.history')); ?>" 
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
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
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
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
    </script>
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
<?php /**PATH C:\Users\User\myFinan\resources\views/budgets/budget.blade.php ENDPATH**/ ?>