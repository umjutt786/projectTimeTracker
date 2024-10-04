<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AdminProjectController;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(AdminMiddleware::class);

Auth::routes();
Route::resource('projects', AdminProjectController::class);


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(AdminMiddleware::class);
