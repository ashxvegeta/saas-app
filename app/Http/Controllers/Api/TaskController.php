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

    public function getTasksByProject($projectId)
    {
        $tenant = app('currentTenant');
        $project = Project::where('id', $projectId)->where('tenant_id', $tenant->id)->first();
        
        if(!$project) {
            return response()->json(['message' => 'Project not found for this tenant'], 404);
        }
        $tasks = Task::where('project_id', $projectId)->latest()->get();

        return response()->json([
            'project' => $project,
            'tasks' => $tasks
        ]);
    }

    public function updateStatus(Request $request , $taskId)
    {
       
       $request->validate([ 'status' => 'required|in:todo,in_progress,done']);
       $tenant = app('currentTenant');
       $task = Task::where('id',$taskId)->whereHas('project',function($query) use ($tenant){
              $query->where('tenant_id', $tenant->id);
       })->first();
       

       if(!$task) {
        return response()->json(['message' => 'Task not found for this tenant'], 404);
       }
       $task->update(['status' => $request->status]);
       
        return response()->json([
          'message' => 'Task status updated successfully',
          'task' => $task
         ]);  
    }


    public function destroy($taskid){
       $tenant = app('currentTenant');
        $task = Task::where('id', $taskid)->whereHas('project', function($query) use ($tenant){
            $query->where('tenant_id', $tenant->id);
        })->first();
        if(!$task) {
            return response()->json(['message' => 'Task not found for this tenant'], 404);
        }
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
    
}
