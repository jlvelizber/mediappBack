<?php

use App\Http\Controllers\Admin\{
    DoctorAvailabilityController as AdminDoctorAvailabilityController
};

use App\Http\Controllers\Doctor\{
    DoctorAvailabilityController as DoctorDoctorAvailabilityController,
    AppointmentController as DoctorAppointmentController,
    PatientController as DoctorPatientController
};
use App\Http\Controllers\Admin\DoctorController;

use App\Http\Controllers\Doctor\DoctorMedicalRecordController;
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
                'only' => ['index', 'store', 'destroy']
            ]
        );

        Route::group([
            'prefix' => 'availabilities',
        ], function () {
            Route::get('get-available-slots', [DoctorDoctorAvailabilityController::class, 'getAvailableSlots']);
        });


        //Patients
        Route::group([
            'prefix' => 'patients',
        ], function () {
            Route::get('paginate', [DoctorPatientController::class, 'paginate']);
        });
        Route::apiResource('patients', DoctorPatientController::class);
        Route::get('patients/{patient}/records', [DoctorPatientController::class, 'records']);
        Route::get('patients/appointment/{patient}', [DoctorPatientController::class, 'getPatientByAppointment']);
        //Appointments
        Route::group([
            'prefix' => 'appointments',
        ], function () {
            Route::get('paginate', [DoctorAppointmentController::class, 'paginate']);
        });
        Route::put('appointments/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus']);
        Route::apiResource('appointments', DoctorAppointmentController::class);

        // Clinical Histories

        Route::apiResource('medical-record', DoctorMedicalRecordController::class, [
            'only' => ['index', 'store', 'show', 'update', 'destroy']
        ]);

    });
});


Route::resource('users', UserController::class);

Route::get('/appointments/{doctorId}/future', [DoctorAppointmentController::class, 'futureAppointments']);



