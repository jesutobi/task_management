<?php

namespace App\Http\Controllers\Task;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectManagerController extends Controller
{
    public function create_project(Request $request)
    {
        try {
            // This vaidation helps to make sure that the incoming request holds all the necessary requirements
            $data = $request->validate([
                'title' => 'required|string|max:255',

            ]);
            $project = Project::create($data);

            return response()->json([
                'status' => 'success',
                'project' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong while creating the project.' . $e->getMessage()
            ], 500);
        }
    }

    public function get_all_project(Request $request)
    {

        try {
            $projects = Project::all();

            return response()->json([
                'status'    => 'success',
                'projects'  => $projects,

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
