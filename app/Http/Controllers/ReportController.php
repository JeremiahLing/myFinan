<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Report;
use App\Mail\MonthlyReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function showReport(Request $request)
    {
        // Parse the selected month into start and end dates
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $userId = Auth::id();

        $search = $request->input('search');
        $reports = Report::where('user_id', $userId)
            ->when($search, function ($query, $search) {
                return $query->where('email', 'LIKE', "%{$search}%")
                            ->orWhere('month', 'LIKE', "%{$search}%")
                            ->orWhere('status', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Filter incomes and expenses by the user ID and selected month
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $incomes = Income::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
        $expenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        // Calculate total income, expense amounts, balance and balance percentage
        $totalIncome = $incomes->sum('amount') ?? 0;
        $totalExpense = $expenses->sum('amount') ?? 0;
        $totalBalance = $totalIncome - $totalExpense;
        $balancePercentage = ($totalIncome > 0) ? round(($totalBalance / $totalIncome) * 100, 2) : 0;

        $transactions = $incomes->map(function ($income) {
            $income->type = 'income';
            return $income;
        })->merge(
            $expenses->map(function ($expense) {
                $expense->type = 'expense';
                return $expense;
            })
        )->sortByDesc('date')->values();


        return view('report', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'totalBalance' => $totalBalance,
            'balancePercentage' => $balancePercentage,
            'selectedMonth' => $month,
            'reports' => $reports,
            'search' => $search,
        ]);
    }

    public function deleteSelected(Request $request)
    {
        try {
            $ids = $request->input('ids');

            if ($ids && is_array($ids)) {
                Report::whereIn('id', $ids)->delete();
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting expenses: ' . $e->getMessage());
        }

        return response()->json(['success' => false, 'message' => 'Unable to delete selected row.']);
    }

    public function sendMonthlyReport(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'month' => 'required|date_format:Y-m',
        ]);

        // Get the selected month from the request
        $month = $request->input('month');
        $user = Auth::user();
        $email = $request->input('email');

        // Filter incomes and expenses for the selected month
        $incomes = Income::whereYear('date', substr($month, 0, 4))
                        ->whereMonth('date', substr($month, 5, 2))
                        ->get();
        $expenses = Expense::whereYear('date', substr($month, 0, 4))
                        ->whereMonth('date', substr($month, 5, 2))
                        ->get();

        // Combine incomes and expenses into a single collection
        $transactions = $incomes->map(function ($income) {
            $income->type = 'income';
            return $income;
        })->merge(
            $expenses->map(function ($expense) {
                $expense->type = 'expense';
                return $expense;
            })
        )->sortByDesc('date')->values();

        // Calculate totals
        $totalIncome = $incomes->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;
        
        Report::create([
            'user_id' => $user->id,
            'email' => $email,
            'month' => $month,
            'status' => 'sent',
            'message' => 'Report sent successfully.',
        ]);
        
        // Send the email and record the email
        try {
            Mail::to($email)->send(new MonthlyReportMail(
                $month,
                $transactions,
                $totalIncome,
                $totalExpense,
                $totalBalance
            ));
            
            return back()->with('success', 'Monthly report sent successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send the monthly report: ' . $e->getMessage()]);
        }        
    }
}
