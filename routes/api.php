<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkLogController;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\FreelancerMiddleware;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login'])->middleware(FreelancerMiddleware::class);

Route::middleware(['auth:sanctum'])->post('/worklogs/start', [WorkLogController::class, 'start']);
Route::middleware(['auth:sanctum'])->post('/worklogs/{id}/pause', [WorkLogController::class, 'pause']);
Route::middleware(['auth:sanctum'])->post('/worklogs/{id}/resume', [WorkLogController::class, 'resume']);
Route::middleware(['auth:sanctum'])->post('/worklogs/{id}/stop', [WorkLogController::class, 'stop']);
Route::middleware(['auth:sanctum'])->post('/worklogs/screenshot', [WorkLogController::class, 'uploadScreenshot']);
Route::middleware(['auth:sanctum'])->get('/projects', [ProjectController::class, 'index']);