<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function create()
    {
        // Retrieve the last `user_item_id` for the logged-in user
        $lastItemId = Auth::user()->budgets()->max('item_id');
    
        // Calculate the next `user_item_id` (start from 1 if no budgets exist)
        $nextItemId = $lastItemId ? $lastItemId + 1 : 1;

        $budgets = Auth::user()->budgets;

        return view('budgets.budget', compact('nextItemId'));
    }

    // Handle form submission
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'category' => 'required|in:Food,Transportation,Salary,Utilities,Entertainment,Healthcare,Other',
            'description' => 'nullable|string',
            'time' => 'nullable',
            'date' => 'nullable|date',
            'amount' => 'nullable|numeric',
        ]);

        // Calculate the next `user_item_id` for the user
        $validatedData['user_id'] = Auth::id();
        $lastItemId = Auth::user()->budgets()->max('item_id');
        $validatedData['item_id'] = $lastItemId ? $lastItemId + 1 : 1;

        // Save the data to the database
        Budget::create($validatedData);

        // Redirect with a success message
        return redirect()->route('budget.create')->with('success', 'Budget saved successfully.');
    }

    //Update budget
    public function update(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'item_id' => 'required|exists:budgets,item_id',
            'category' => 'required|in:Food,Transportation,Salary,Utilities,Entertainment,Healthcare,Other',
            'description' => 'nullable|string',
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        // Find the budget item by ID
        $budget = Budget::where('item_id', $request->item_id)->firstOrFail();

        // Update the budget item
        $budget->category = $request->category;
        $budget->description = $request->description;
        $budget->time = $request->time;
        $budget->date = $request->date;
        $budget->amount = $request->amount;
        $budget->save();

        // Redirect back with success message
        return response()->json(['success' => true, 'message' => 'Item updated successfully.']);
    }

    public function history(Request $request)
    {
        $selectedMonth = $request->get('month');

        // Fetch expenses for the authenticated user
        $budgets = Auth::user()->budgets()
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

        return view('/budgets/budgethistory', compact('budgets', 'months', 'selectedMonth'));
    }

    public function deleteSelected(Request $request)
    {
        try {
            $ids = $request->input('ids');

            if ($ids && is_array($ids)) {
                Budget::whereIn('id', $ids)->delete();
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting budgets: ' . $e->getMessage());
        }

        return response()->json(['success' => false, 'message' => 'Unable to delete selected budgets.']);
    }
}
