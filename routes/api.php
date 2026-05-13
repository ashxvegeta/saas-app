<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'set.tenant'])->group(function () {

   Route::get('/test-tenant', function () {
      return response()->json([ 'tenant' => app('currentTenant')]);
   });
   Route::post('/projects', [ProjectController::class, 'store']);
   Route::get('/projects', [ProjectController::class, 'index']);
   Route::post('/tasks', [TaskController::class, 'store']);
});

