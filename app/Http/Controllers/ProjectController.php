<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Create a new project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProject(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
        ]);

        $project = Project::find($request->input('id'));

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found',
            ], 404);
        }

        // Delete related timesheets
        Timesheet::where('project_id', $project->id)->delete();

        // Delete the project
        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Project and related timesheets deleted successfully',
        ]);
    }
    public function updateProject(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $project = Project::find($request->input('id'));
        $project->name = $request->input('name');
        $project->department = $request->input('department');
        $project->start_date = $request->input('start_date');
        $project->end_date = $request->input('end_date');
        $project->status = $request->input('status');
        $project->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Project updated successfully',
            'project' => $project,
        ]);
    }
    public function getProjectById($id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Project retrieved successfully',
            'project' => $project,
        ]);
    }
     public function getAllProjects()
    {
        $projects = Project::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Projects retrieved successfully',
            'projects' => $projects,
        ]);
    }
    public function createProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $project = Project::create([
            'name' => $request->input('name'),
            'department' => $request->input('department'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Project created successfully',
            'project' => $project,
        ], 201);
    }
}
