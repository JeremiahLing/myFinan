<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // Display the staff management page
    public function index()
    {
        $staff = auth()->user()->staff()->orderBy('id', 'asc')->paginate(10);
        
        // Generate the next staff ID
        $latestStaff = Staff::latest('staff_id')->first();
        if ($latestStaff) {
            preg_match('/\d+$/', $latestStaff->staff_id, $matches);
            $nextId = $matches ? intval($matches[0]) + 1 : 1;
        } else {
            $nextId = 1;
        }
        $newStaffId = str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('managements.staff', compact('staff', 'newStaffId'));
    }

    // Store a new staff record
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'phone' => 'required|string|max:15',
            'ic_no' => 'required|unique:staff,ic_no',
            'salary' => 'required|numeric|min:0',
            'position' => 'required|string|max:255',
        ]);

        // Extract the latest staff_id and increment it
        $latestStaff = Staff::latest('staff_id')->first();
        if ($latestStaff) {
            // Extract the numerical part of the staff_id
            preg_match('/\d+$/', $latestStaff->staff_id, $matches);
            $nextId = $matches ? intval($matches[0]) + 1 : 1;
        } else {
            $nextId = 1;
        }

        // Generate the new staff_id with zero padding
        $newStaffId = str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $validated['staff_id'] = $newStaffId; 
        $validated['user_id'] = auth()->id();

        Staff::create($validated);

        return redirect()->route('staff')->with('success', 'Staff added successfully.');
    }

    //create details
    public function create()
    {
        // Fetch the latest staff_id and calculate the next one
        $latestStaff = Staff::latest('staff_id')->first();
        if ($latestStaff) {
            preg_match('/\d+$/', $latestStaff->staff_id, $matches);
            $nextId = $matches ? intval($matches[0]) + 1 : 1;
        } else {
            $nextId = 1;
        }

        $newStaffId = str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('staff.create', compact('newStaffId'));
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('managements.edit', compact('staff'));
    }

    //update details
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $id,
            'phone' => 'required|string|max:15',
            'ic_no' => 'required|unique:staff,ic_no,' . $id,
            'salary' => 'required|numeric|min:0',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $staff = Staff::findOrFail($id);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('staff_photos', 'public');
            $staff->photo = $photoPath;
        }

        $staff->update($request->except('photo'));

        return redirect()->route('staff')->with('success', 'Staff updated successfully.');
    }

    // Delete a staff record
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return redirect()->back()->with('success', 'Staff deleted successfully!');
    }
}
