<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function create()
    {
        // Retrieve the last `user_item_id` for the logged-in user
        $lastItemId = Auth::user()->incomes()->max('item_id');
    
        // Calculate the next `user_item_id` (start from 1 if no incomes exist)
        $nextItemId = $lastItemId ? $lastItemId + 1 : 1;

        $incomes = Auth::user()->incomes;

        return view('/incomes/income', compact('nextItemId'));
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
        $lastItemId = Auth::user()->incomes()->max('item_id');
        $validatedData['item_id'] = $lastItemId ? $lastItemId + 1 : 1;

        Income::create($validatedData);

        return redirect()->route('income.create')->with('success', 'Income saved successfully.');
    }

    //Update income
    public function update(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'item_id' => 'required|exists:incomes,item_id',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'time' => 'required|date_format:H:i:s',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        // Find the income item by ID
        $income = Income::where('item_id', $request->item_id)->firstOrFail();

        // Update the income item
        $income->item_name = $request->item_name;
        $income->quantity = $request->quantity;
        $income->description = $request->description;
        $income->time = $request->time;
        $income->date = $request->date;
        $income->amount = $request->amount;
        $income->save();

        // Redirect back with success message
        return response()->json(['success' => true, 'message' => 'Item updated successfully.']);
    }

    public function history(Request $request)
    {
        $selectedMonth = $request->get('month');

        // Fetch expenses for the authenticated user
        $incomes = Auth::user()->incomes()
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

        return view('/incomes/incomehistory', compact('incomes', 'months', 'selectedMonth'));
    }

    public function deleteSelected(Request $request)
    {
        try {
            $ids = $request->input('ids');

            if ($ids && is_array($ids)) {
                Income::whereIn('id', $ids)->delete();
                return response()->json(['success' => true]);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting incomes: ' . $e->getMessage());
        }

        return response()->json(['success' => false, 'message' => 'Unable to delete selected incomes.']);
    }
}
