<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;

Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum', 'set.tenant'])->group(function () {
   
   Route::get('/test-tenant', function () {
      return response()->json([ 'tenant' => app('currentTenant')]);
   });
   Route::post('/projects', [ProjectController::class, 'store']);
});