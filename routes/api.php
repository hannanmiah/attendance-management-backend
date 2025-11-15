<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->name('auth.')->controller(AuthenticationController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->middleware('auth:sanctum')->name('logout');
});

Route::apiResource('students', StudentController::class)->middleware('auth:sanctum');
Route::get('attendances',[AttendanceController::class,'index'])->middleware('auth:sanctum');
Route::post('attendance', [AttendanceController::class, 'store'])->middleware('auth:sanctum');
Route::get('attendance/report', [AttendanceController::class, 'monthlyReport'])->middleware('auth:sanctum');
Route::get('attendance/statistics', [AttendanceController::class, 'statistics'])->middleware('auth:sanctum');
