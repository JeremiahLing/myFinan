<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Staff;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $attendance = Attendance::with('staff')
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        $staff = Staff::where('user_id', $user->id)->get();

        return view('managements.attendance', compact('attendance', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'date' => 'required|date',
        ]);

        $attendance = Attendance::where('staff_id', $validated['staff_id'])
            ->where('date', $validated['date'])
            ->first();

        if ($attendance) {
            $attendance->delete();
            return response()->json(['success' => true, 'removed' => true]);
        } else {
            Attendance::create([
                'staff_id' => $validated['staff_id'],
                'date' => $validated['date'],
            ]);
            return response()->json(['success' => true, 'added' => true]);
        }
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return redirect()->route('attendance')->with('success', 'Attendance record deleted successfully.');
    }
}