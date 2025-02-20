<!DOCTYPE html>
<html>
<head>
    <title>Monthly Financial Report</title>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <h1>Monthly Financial Report - <?php echo e($month); ?></h1>
    </div>
    <p>Total Income : RM <?php echo e(number_format($totalIncome, 2)); ?></p>
    <p>Total Expense: RM <?php echo e(number_format($totalExpense, 2)); ?></p>
    <p>Balance      : RM <?php echo e(number_format($totalBalance, 2)); ?></p>

    <h2>Transactions</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($transaction->item_name); ?></td>
                    <td><?php echo e($transaction->description); ?></td>
                    <td style="color: <?php echo e($transaction->type === 'income' ? 'green' : 'red'); ?>">
                        <?php echo e($transaction->type === 'income' ? '+' : '-'); ?> RM<?php echo e(number_format($transaction->amount, 2)); ?>

                    </td>
                    <td><?php echo e($transaction->date); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\User\myFinan\resources\views/emails/monthly-report.blade.php ENDPATH**/ ?>