<?php

use App\Http\Controllers\Api\DocumentsController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\TasksController;
use App\Http\Controllers\Api\TodoController;
use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EanProcessingController;
use Illuminate\Support\Facades\Log;


Route::post('/login', [LoginController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/test', [EanProcessingController::class, 'testMethod']);

    Route::get('/get_user_info', [LoginController::class, 'getUserInfo']);
    
    Route::get('/get_task_list', [TasksController::class, 'listTasks']);
    Route::get('/get_task_details/{id}', [TasksController::class, 'taskDetails']);
    Route::get('/task_status', [TasksController::class, 'getAllStatuses']);
    Route::post('/update_status/{uuid}', [TasksController::class, 'updateStatus']);

    
    Route::get('/get_todo_list', [TodoController::class, 'listTodos']);
    Route::get('/get_todo_details/{id}', [TodoController::class, 'todoDetails']);
    Route::get('/todo_status', [TodoController::class, 'getAllStatuses']);
    Route::post('/update_todo_status/{uuid}', [TodoController::class, 'updateStatus']);
    Route::get('/count_todos', [TodoController::class, 'getTodoCountsByStatus']);
    Route::post('/store', [TodoController::class, 'store']);
    Route::post('/update/{uuid}', [TodoController::class, 'update']);
    Route::post('/todos/add_note/{todoUuid}/save', [TodoController::class, 'addNote']);
    Route::delete('/todos/delete_note/{noteId}', [TodoController::class, 'deleteNote']);
    Route::get('/todos/get_notes/{todoUuid}', [TodoController::class, 'getNotes']);
    
    Route::get('/get-documents', [DocumentsController::class, 'loadDocuments']);
    Route::get('/get-documents-details/{uuid}', [DocumentsController::class, 'getParentDocument']);
    Route::get('/get-sub-documents-details/{uuid}', [DocumentsController::class, 'subDocumentList']);
    Route::get('/download-file/{uuid}', [DocumentsController::class, 'downloadAttachment']);

    Route::post('/logout', [LoginController::class, 'logout']);

});

// Catch-all route for debugging
Route::fallback(function(){
    Log::error('Route not found: ' . request()->url());
    return response()->json(['message' => 'Route not found.'], 404);
});