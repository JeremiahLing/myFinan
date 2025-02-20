<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function create()
    {
        // Retrieve the last `user_item_id` for the logged-in user
        $lastItemId = Auth::user()->expenses()->max('item_id');
            
        // Calculate the next `user_item_id` (start from 1 if no expenses exist)
        $nextItemId = $lastItemId ? $lastItemId + 1 : 1;

        $expenses = Auth::user()->expenses;

        return view('/expenses/expense', compact('nextItemId'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'time' => 'nullable',
            'date' => 'nullable|date',
            'amount' => 'nullable|numeric',
        ]);

        $validatedData['user_id'] = Auth::id();
        $lastItemId = Auth::user()->expenses()->max('item_id');
        $validatedData['item_id'] = $lastItemId ? $lastItemId + 1 : 1;

        Expense::create($validatedData);

        return redirect()->route('expense.create')->with('success', 'Expense saved successfully.');
    }

    //Update expense
    public function update(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'item_id' => 'required|exists:expenses,item_id',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        // Find the expense item by ID
        $expense = Expense::where('item_id', $request->item_id)->firstOrFail();

        // Update the expense item
        $expense->item_name = $request->item_name;
        $expense->quantity = $request->quantity;
        $expense->description = $request->description;
        $expense->time = $request->time;
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->save();

        return response()->json(['success' => true, 'message' => 'Item updated successfully.']);
    }

    public function history(Request $request)
    {
        $selectedMonth = $request->get('month');

        // Fetch expenses for the authenticated user
        $expenses = Auth::user()->expenses()
            ->when($selectedMonth, function ($query, $selectedMonth) {
                return $query->whereMonth('date', $selectedMonth);
            })
            ->get();

        // List of months
        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        return view('/expenses/expensehistory', compact('expenses', 'months', 'selectedMonth'));
    }

    public function deleteSelected(Request $request)
    {
        try {
            $ids = $request->input('ids');

            if ($ids && is_array($ids)) {
                Expense::whereIn('id', $ids)->delete();
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting expenses: ' . $e->getMessage());
        }

        return response()->json(['success' => false, 'message' => 'Unable to delete selected expenses.']);
    }
}
