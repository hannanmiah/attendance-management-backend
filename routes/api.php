<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('students', StudentController::class)->middleware('auth:sanctum');
Route::post('attendance', [AttendanceController::class, 'store'])->middleware('auth:sanctum');
Route::get('attendance/report', [AttendanceController::class, 'monthlyReport'])->middleware('auth:sanctum');
Route::get('attendance/statistics', [AttendanceController::class, 'statistics'])->middleware('auth:sanctum');
