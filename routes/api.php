<?php

use App\Http\Controllers\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', "throttle:5,1"])->post('/courses', [CourseController::class, 'create'])->name('create');
Route::middleware("throttle:5,1")->get('/courses/{id?}', [CourseController::class, 'read'])->name('read');
Route::middleware(['auth:sanctum', "throttle:5,1"])->put('/courses/{id}', [CourseController::class, 'update'])->name('update');
Route::middleware(['auth:sanctum', "throttle:5,1"])->delete('/courses/{id}', [CourseController::class, 'delete'])->name('delete');
