<?php

namespace App\Http\Controllers\Task;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReOrderController extends Controller
{


    public function reorder_task(Request $request)
    {
        try {

            // this validation makes sure that incoming request is strictly an array and th is are present in the task table
            $validated = $request->validate([
                'tasks'   => 'required|array',
                'tasks.*' => 'integer|exists:task,id'
            ]);

            $tasks = $validated['tasks'];

            foreach ($tasks as $index => $taskId) {
                $task = Task::findOrFail($taskId);
                $task->update(['priority' => $index + 1]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to reorder tasks. Please try again.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function get_all_task(Request $request)
    {

        try {
            $validated = $request->validate([
                'project_id' => 'nullable|exists:projects,id'
            ]);

            $projectId = $validated['project_id'] ?? null;

            $tasks = Task::when($projectId, fn($q) => $q->where('project_id', $projectId))
                ->orderBy('priority')
                ->get();

            return response()->json([
                'status'    => 'success',
                'tasks'     => $tasks,
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
