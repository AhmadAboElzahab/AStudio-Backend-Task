<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectUserController extends Controller
{
    public function assignUserToProject(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please log in to perform this action.',
            ], 401);
        }

        $user = Auth::user();

        $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::find($request->input('project_id'));

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found',
            ], 404);
        }

        $user->projects()->attach($project);

        return response()->json([
            'status' => 'success',
            'message' => 'User assigned to project successfully',
            'user' => $user,
            'project' => $project,
        ]);
    }
}
