<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum', 'set.tenant'])->get('/test-tenant', function () {
   return response()->json(['tenant' => app('currentTenant')]);
});