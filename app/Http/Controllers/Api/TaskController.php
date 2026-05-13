<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
           'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $tenant = app('currentTenant');
        $project = Project::where('id', $request->project_id)->where('tenant_id', $tenant->id)->first();

        if(!$project) {
            return response()->json(['message' => 'Project not found for this tenant'], 404);
        }
        $task = Task::create([
            'project_id' =>  $project->id,
            'assigned_to' => $request->assigned_to,
            'title' => $request->title,
            'status' => 'todo'
        ]);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => $task
        ], 201);
    }
}
