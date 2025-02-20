<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\InspectController;
use App\Models\Report;
use App\Models\Invoice;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Budget Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/budgets/budget', [BudgetController::class, 'create'])->name('budget.create');
    Route::post('/budgets/budget', [BudgetController::class, 'store'])->name('budget.store');
    Route::get('/budget/history', [BudgetController::class, 'history'])->name('budget.history');
    Route::put('/budget/update', [BudgetController::class, 'update'])->name('budget.update');
    Route::post('/budgets/delete-selected', [BudgetController::class, 'deleteSelected'])->name('budgets.deleteSelected');
});

// Expense Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/expenses/expense', [ExpenseController::class, 'create'])->name('expense.create');
    Route::post('/expenses/expense', [ExpenseController::class, 'store'])->name('expense.store');
    Route::get('/expense/history', [ExpenseController::class, 'history'])->name('expense.history');
    Route::put('/expense/update', [ExpenseController::class, 'update'])->name('expense.update');
    Route::post('/expenses/delete-selected', [ExpenseController::class, 'deleteSelected'])->name('expenses.deleteSelected');
});

// Income Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/incomes/income', [IncomeController::class, 'create'])->name('income.create');
    Route::post('/incomes/income', [IncomeController::class, 'store'])->name('income.store');
    Route::get('/income/history', [IncomeController::class, 'history'])->name('income.history');
    Route::put('/income/update', [IncomeController::class, 'update'])->name('income.update');
    Route::post('/incomes/delete-selected', [IncomeController::class, 'deleteSelected'])->name('incomes.deleteSelected');
});

//Report
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/report', [ReportController::class, 'showReport'])->name('report');
    Route::post('/send-monthly-report', [ReportController::class, 'sendMonthlyReport'])->name('sendMonthlyReport');
    Route::get('/inspect', [InspectController::class, 'inspect'])->name('inspect');
    Route::get('/inspect-report', [InspectController::class, 'getYearlyReport'])->name('inspect.report');
    Route::get('/inspect-details', [InspectController::class, 'getDetails'])->name('inspect.details');
    Route::post('/reports/delete-selected', [ReportController::class, 'deleteSelected'])->name('reports.deleteSelected');

});
    
//Invoice
Route::group(['middleware' => ['auth', 'verified']], function () {
    // Invoice List Route
    Route::get('/invoices/invoice', [InvoiceController::class, 'index'])->name('invoice');

    // Create new Invoice
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/store', [InvoiceController::class, 'store'])->name('invoices.store');

    // Show Invoice Details
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

    //Edit Invoice Details
    Route::get('/invoices/{id}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update');

    Route::resource('invoices', InvoiceController::class);

    // Preview Invoice
    Route::post('/invoices/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');

    // Send Invoice
    Route::post('/invoice/send', [InvoiceController::class, 'sendInvoice'])->name('invoice.send');

    Route::post('/delete-selected-items', [InvoiceController::class, 'deleteSelected'])->name('invoices.deleteSelected');
});

//Management
Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/managements/staff', [StaffController::class, 'index'])->name('staff');
    Route::post('/managements/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');

    // Attendence
    Route::get('/managements/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/managements/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
