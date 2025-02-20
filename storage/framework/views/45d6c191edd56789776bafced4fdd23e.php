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
            <?php echo e(__('Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12 min-h-screen" style="background-color: #CCAAFFaa;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" style="background-color:rgba(255, 255, 255, 0.33);">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center text-xl font-semibold">
                    <?php if(Auth::check()): ?>
                        <p>Welcome, <?php echo e(Auth::user()->name); ?>!</p>
                    <?php else: ?>
                        <p>Please log in to see your profile.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="animate__animated animate__fadeInUp">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center font-bold text-2xl">
                    <p>Quick Access Toward:</p>

                    <div class="grid grid-cols-3 gap-6 p-6 text-center text-2xl font-bold">
                        <!-- Budget -->
                        <a
                            href="<?php echo e(route('budget.create')); ?>"
                            class="group relative flex flex-col items-center justify-center h-40 text-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 transform hover:scale-105"
                            style="background-image: url('<?php echo e(asset('budget.jpeg')); ?>'); background-size: cover; background-position: center;"
                        >
                            <!-- Overlay for dim effect -->
                            <div class="absolute inset-0 bg-black opacity-50 group-hover:opacity-30 transition duration-300"></div>
                            <span class="relative text-3xl font-semibold">Budget</span>
                            <p class="relative text-sm text-gray-200">
                                Help to record your estimation of revenue and expenses made for a specified future period.
                            </p>
                        </a>

                        <!-- Expense -->
                        <a
                            href="<?php echo e(route('expense.create')); ?>"
                            class="group relative flex flex-col items-center justify-center h-40 text-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 transform hover:scale-105"
                            style="background-image: url('<?php echo e(asset('expense.png')); ?>'); background-size: cover; background-position: center;"
                        >
                            <!-- Overlay for dim effect -->
                            <div class="absolute inset-0 bg-black opacity-50 group-hover:opacity-30 transition duration-300"></div>
                            <span class="relative text-3xl font-semibold">Expense</span>
                            <p class="p-2 relative text-sm text-gray-200">
                                Record an outflow of money, or any form of fortune in general, to another person or group as payment for an item, service, or other category of costs.
                            </p>
                        </a>

                        <!-- Income -->
                        <a
                            href="<?php echo e(route('income.create')); ?>"
                            class="group relative flex flex-col items-center justify-center h-40 text-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 transform hover:scale-105"
                            style="background-image: url('<?php echo e(asset('income.jpg')); ?>'); background-size: cover; background-position: center;"
                        >
                            <!-- Overlay for dim effect -->
                            <div class="absolute inset-0 bg-black opacity-50 group-hover:opacity-30 transition duration-300"></div>
                            <span class="relative text-3xl font-semibold">Income</span>
                            <p class="p-2 relative text-sm text-gray-200">
                                Record the money you receive in exchange for your labor or goods.
                            </p>
                        </a>

                        <!-- Financial Report -->
                        <a
                            href="<?php echo e(route('report')); ?>"
                            class="group relative flex flex-col items-center justify-center h-40 text-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 transform hover:scale-105"
                            style="background-image: url('<?php echo e(asset('report.jpeg')); ?>'); background-size: cover; background-position: center;"
                        >
                            <!-- Overlay for dim effect -->
                            <div class="absolute inset-0 bg-black opacity-50 group-hover:opacity-30 transition duration-300"></div>
                            <span class="relative text-3xl font-semibold">Financial Report</span>
                            <p class="p-2 relative text-sm text-gray-200">
                                A historical financial statements for you. Relevant financial information is presented in a structured manner and in a form which is easy to understand.
                            </p>
                        </a>

                        <!-- Invoice -->
                        <a
                            href="<?php echo e(route('invoice')); ?>"
                            class="group relative flex flex-col items-center justify-center h-40 text-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 transform hover:scale-105"
                            style="background-image: url('<?php echo e(asset('invoice.png')); ?>'); background-size: cover; background-position: center;"
                        >
                            <!-- Overlay for dim effect -->
                            <div class="absolute inset-0 bg-black opacity-50 group-hover:opacity-30 transition duration-300"></div>
                            <span class="relative text-3xl font-semibold">Invoice</span>
                            <p class="p-2 relative text-sm text-gray-200">
                                A commercial document issued for you and the seller relating to a sale transaction.
                            </p>
                        </a>

                        <!-- Staff Management -->
                        <a
                            href="<?php echo e(route('staff')); ?>"
                            class="group relative flex flex-col items-center justify-center h-40 text-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 transform hover:scale-105"
                            style="background-image: url('<?php echo e(asset('staff.jpg')); ?>'); background-size: cover; background-position: center;"
                        >
                            <!-- Overlay for dim effect -->
                            <div class="absolute inset-0 bg-black opacity-50 group-hover:opacity-30 transition duration-300"></div>
                            <span class="relative text-3xl font-semibold">Staff Management</span>
                            <p class="p-2 relative text-sm text-gray-200">
                                Know your staff to get better in relationship. Try to meet some friend.
                            </p>
                        </a>

                        <!-- Attendance -->
                        <a
                            href="<?php echo e(route('attendance')); ?>"
                            class="group relative flex flex-col items-center justify-center h-40 text-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 transform hover:scale-105"
                            style="background-image: url('<?php echo e(asset('attendance.jpeg')); ?>'); background-size: cover; background-position: center;"
                        >
                            <!-- Overlay for dim effect -->
                            <div class="absolute inset-0 bg-black opacity-50 group-hover:opacity-30 transition duration-300"></div>
                            <span class="relative text-3xl font-semibold">Attendance</span>
                            <p class="p-2 relative text-sm text-gray-200">
                                Clock-in, clock-out. Track attendance of your teammates. See weither there is anyone catching the snakes.
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
<?php endif; ?>
<?php /**PATH C:\Users\User\myFinan\resources\views/dashboard.blade.php ENDPATH**/ ?>