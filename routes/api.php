<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

require __DIR__ . '/auth.php';


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('users', UserController::class);
Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/appointments/{doctorId}/future', [AppointmentController::class, 'futureAppointments']);
