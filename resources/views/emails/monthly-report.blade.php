<!DOCTYPE html>
<html>
<head>
    <title>Monthly Financial Report</title>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <h1>Monthly Financial Report - {{ $month }}</h1>
    </div>
    <p>Total Income : RM {{ number_format($totalIncome, 2) }}</p>
    <p>Total Expense: RM {{ number_format($totalExpense, 2) }}</p>
    <p>Balance      : RM {{ number_format($totalBalance, 2) }}</p>

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
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->item_name }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td style="color: {{ $transaction->type === 'income' ? 'green' : 'red' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }} RM{{ number_format($transaction->amount, 2) }}
                    </td>
                    <td>{{ $transaction->date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
