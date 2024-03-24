<?php
namespace App\Http\Controllers;

use App\Models\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TimesheetController extends Controller
{
    public function getTimesheetById($id)
    {
        try {
            $user = Auth::user();
            $timesheet = Timesheet::findOrFail($id);
            if ($timesheet->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            return response()->json([
                'status' => 'success',
                'timesheet' => $timesheet,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Timesheet not found'], 404);
        }
    }
      public function getUserTimesheets()
    {
        
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            $user = Auth::user();
            $timesheets = $user->timesheets()->get();
            return response()->json([
                'status' => 'success',
                'timesheets' => $timesheets,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve timesheets'], 500);
        }
    }
    public function createTimesheet(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }
    $user = Auth::user();

    $validatedData = $request->validate([
        'project_id' => 'required|integer',
        'task_name' => 'required|string',
        'date' => 'required|date',
        'hours' => 'required|integer|min:1',
    ]);
    $existingTimesheet = Timesheet::where('user_id', $user->id)
        ->where('project_id', $validatedData['project_id'])
        ->first();

    if ($existingTimesheet) {
        return response()->json(['error' => 'Timesheet already exists for this user and project'], 400);
    }

    $timesheet = new Timesheet();
    $timesheet->user_id = $user->id;
    $timesheet->project_id = $validatedData['project_id'];
    $timesheet->task_name = $validatedData['task_name'];
    $timesheet->date = $validatedData['date'];
    $timesheet->hours = $validatedData['hours'];

    try {
        $timesheet->save();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to create timesheet'], 500);
    }
    return response()->json([
        'status' => 'success',
        'message' => 'Timesheet created successfully',
        'timesheet' => $timesheet,
    ], 201);
}

public function updateTimesheet(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }
    $user = Auth::user();
    $timesheet = Timesheet::where('user_id', $user->id)->first();

    if (!$timesheet) {
        return response()->json(['error' => 'Timesheet not found for this user'], 404);
    }

    $validatedData = $request->validate([
        'task_name' => 'required|string',
        'date' => 'required|date',
        'hours' => 'required|integer|min:1',
    ]);

    $timesheet->task_name = $validatedData['task_name'];
    $timesheet->date = $validatedData['date'];
    $timesheet->hours = $validatedData['hours'];

    try {
        $timesheet->save();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to update timesheet'], 500);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Timesheet updated successfully',
        'timesheet' => $timesheet,
    ], 200);
}

public function deleteTimesheet()
{
    if (!Auth::check()) {
        return response()->json(['error' => 'User not authenticated'], 401);
    }
    $user = Auth::user();
    $timesheet = Timesheet::where('user_id', $user->id)->first();

    if (!$timesheet) {
        return response()->json(['error' => 'Timesheet not found for this user'], 404);
    }
    try {
        $timesheet->delete();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete timesheet'], 500);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Timesheet deleted successfully',
    ], 200);
}
}