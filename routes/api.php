<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

require __DIR__ . '/auth.php';


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Bienvenido al Dashboard']);
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', function () {
            return response()->json(['message' => 'Área de Admin']);
        });
    });

    Route::middleware(['role:doctor'])->group(function () {

        Route::apiResource('appointments', AppointmentController::class);

        Route::get('/doctor', function () {
            return response()->json(['message' => 'Área de Doctor']);
        });
    });
});


Route::apiResource('patients', PatientController::class);
Route::resource('users', UserController::class);
Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/appointments/{doctorId}/future', [AppointmentController::class, 'futureAppointments']);



