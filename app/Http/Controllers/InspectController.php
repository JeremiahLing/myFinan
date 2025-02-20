<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InspectController extends Controller
{
    public function getDetails(Request $request)
    {
        $year = intval($request->get('year'));
        $month = $request->get('month');
        $type = $request->get('type');
        $userId = Auth::id();

        // Validate inputs
        if (!in_array($month, [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ]) || !in_array($type, ['Expenses', 'Incomes'])) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $monthIndex = array_search($month, [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ]) + 1;

        $table = $type === 'Expenses' ? 'expenses' : 'incomes';

        $details = DB::table($table)
            ->select('item_name', 'quantity', 'description', 'amount', 'date')
            ->whereYear('date', $year)
            ->whereMonth('date', $monthIndex)
            ->where('user_id', $userId)
            ->get();

        return response()->json(['details' => $details]);
    }

    public function getYearlyReport(Request $request)
    {
        $year = intval($request->get('year', date('Y')));
        $userId = Auth::id();

        // Validate year
        if ($year < 2000 || $year > intval(date('Y'))) {
            return response()->json(['error' => 'Invalid year'], 400);
        }

        $chartData = $this->getChartDataForYear($year);

        if (empty($chartData['labels']) || empty($chartData['expenses']) || empty($chartData['incomes'])) {
            return response()->json(['error' => 'No data found'], 404);
        }

        $totalBudget = DB::table('budgets')
            ->whereYear('date', $year)
            ->where('user_id', $userId)
            ->sum('amount');

        $expenses = DB::table('expenses')
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $year)
            ->where('user_id', Auth::id())
            ->groupByRaw('MONTH(date)')
            ->pluck('total', 'month');

        $incomes = DB::table('incomes')
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $year)
            ->where('user_id', Auth::id())
            ->groupByRaw('MONTH(date)')
            ->pluck('total', 'month');

        return response()->json(['chartData' => $chartData, 'totalBudget' => $totalBudget]);
    }

    private function getChartDataForYear($year)
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Fetch expenses grouped by month
        $expenses = DB::table('expenses')
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $year)
            ->where('user_id', Auth::id())
            ->groupByRaw('MONTH(date)')
            ->pluck('total', 'month');

        // Fetch incomes grouped by month
        $incomes = DB::table('incomes')
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $year)
            ->where('user_id', Auth::id())
            ->groupByRaw('MONTH(date)')
            ->pluck('total', 'month');

        // Map the results to ensure all 12 months are included
        $expensesData = array_map(function ($month) use ($expenses) {
            return $expenses[$month] ?? 0;
        }, range(1, 12));

        $incomesData = array_map(function ($month) use ($incomes) {
            return $incomes[$month] ?? 0;
        }, range(1, 12));

        return [
            'labels' => $months,
            'expenses' => $expensesData,
            'incomes' => $incomesData,
        ];
    }

    public function inspect(Request $request)
    {
        // Get the current year
        $year = date('Y');
        $userId = Auth::id();

        // Retrieve total budget for the selected year
        $totalBudget = DB::table('budgets')
            ->whereYear('date', $year)
            ->where('user_id', $userId)
            ->sum('amount');

        // Retrieve expenses grouped by month
        $expenses = DB::table('expenses')
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $year)
            ->where('user_id', Auth::id())
            ->groupByRaw('MONTH(date)')
            ->pluck('total', 'month');

        // Retrieve incomes grouped by month
        $incomes = DB::table('incomes')
            ->selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $year)
            ->where('user_id', Auth::id())
            ->groupByRaw('MONTH(date)')
            ->pluck('total', 'month');

        // Map the results to ensure all 12 months are included
        $expensesData = array_map(function ($month) use ($expenses) {
            return $expenses[$month] ?? 0;
        }, range(1, 12));

        $incomesData = array_map(function ($month) use ($incomes) {
            return $incomes[$month] ?? 0;
        }, range(1, 12));

        // Prepare data for the chart
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        $chartData = [
            'labels' => $months,
            'expenses' => array_map(function ($month) use ($expenses) {
                return $expenses[$month] ?? 0;
            }, range(1, 12)),
            'incomes' => array_map(function ($month) use ($incomes) {
                return $incomes[$month] ?? 0;
            }, range(1, 12)),
        ];

        return view('inspect', compact('chartData', 'year', 'totalBudget'));
    }
}