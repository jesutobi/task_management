<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskManagerController extends Controller
{
    public function create_task(Request $request)
    {
        try {
            // This vaidation helps to make sure that the incoming request holds all the necessary requirements
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'project_id' => 'nullable|exists:projects,id',
            ]);

            // I calculated the highest priority number for the project so that
            // the new task can be placed at the bottom of the list.
            // For example, if the highest priority is 3, the new task gets 4.
            $Priority = Task::where('project_id', $data['project_id'] ?? null)
                ->max('priority') ?? 0;

            $data['priority'] = $Priority + 1;

            Task::create($data);

            return response()->json(['status' => 'Task created']);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong while creating the task.' . $e->getMessage()
            ], 500);
        }
    }

    public function update_task(Request $request, $id)
    {
        try {

            // This vaidation helps to make sure that the incoming request holds all the necessary requirements
            $data = $request->validate([
                'title' => 'required|string|max:255',
            ]);

            $task = Task::findOrFail($id);
            $task->update($data);


            return response()->json(['status' => 'Task updated successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update task: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete_task($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json(['status' => 'Task deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete task: ' . $e->getMessage()
            ], 500);
        }
    }
}
