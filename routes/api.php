<?php

use App\Http\Controllers\Task\ProjectManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\ReOrderController;
use App\Http\Controllers\Task\TaskManagerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('tasks')->group(function () {
    Route::post('/create', [TaskManagerController::class, 'create_task']);
    Route::put('/update/{id}', [TaskManagerController::class, 'update_task']);
    Route::delete('/delete/{id}', [TaskManagerController::class, 'delete_task']);
    Route::post('reorder', [ReOrderController::class, 'reorder_task']);
    Route::get('all', [ReOrderController::class, 'get_all_task']);
});

Route::prefix('projects')->group(function () {
    Route::get('all', [ProjectManagerController::class, 'get_all_project']);
    Route::post('/create', [ProjectManagerController::class, 'create_project']);
    Route::get('tasks/project/{id}', [ProjectManagerController::class, 'get_task_by_Project']);
});
