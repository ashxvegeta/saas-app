<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{


    public function  index(){
        $tenant = app('currentTenant');
        $projects = Project::where('tenant_id', $tenant->id)->get();

        return response()->json([
            'projects' => $projects
        ]);
    }

    //
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
        ]);

        $tenant = app('currentTenant');
        $project = Project::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project
        ], 201);
        
    }
}
