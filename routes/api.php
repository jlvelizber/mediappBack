<?php

use App\Http\Controllers\Admin\{
    DoctorAvailabilityController as AdminDoctorAvailabilityController
};
use App\Http\Controllers\Doctor\{
    DoctorAvailabilityController as DoctorDoctorAvailabilityController
};
use App\Http\Controllers\Doctor\AppointmentController;
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
    // Admin Routes
    Route::middleware(['role:admin'])->group(function () {

        Route::apiResource('/doctors', DoctorController::class);

        Route::apiResource(
            'doctor.availabilities',
            AdminDoctorAvailabilityController::class,
            [
                'only' =>
                    ['index', 'store', 'update', 'destroy']
            ]
        );
    });




    // Doctor Rooutes
    Route::middleware(['role:doctor'])->group(function () {

        // available times for the doctor
        Route::apiResource(
            'availabilities',
            DoctorDoctorAvailabilityController::class,
            [
                'only' => ['index', 'store'],
            ]
        );

        Route::apiResource('appointments', AppointmentController::class);

        Route::get('/doctor', function () {
            return response()->json(['message' => 'Área de Doctor']);
        });

    });
});


Route::apiResource('patients', PatientController::class);
Route::resource('users', UserController::class);

Route::get('/appointments/{doctorId}/future', [AppointmentController::class, 'futureAppointments']);



