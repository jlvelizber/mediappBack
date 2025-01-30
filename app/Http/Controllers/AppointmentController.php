<?php

namespace App\Http\Controllers;

use App\Http\Requests\Appointment\AppointmentStoreRequest;
use App\Http\Requests\Appointment\AppointmentUpdateRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $appointments = $this->appointmentService->getAllAppointments();
        return AppointmentResource::collection($appointments)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentStoreRequest $request)
    {
        $appointment = $this->appointmentService->createAppointment($request->validated());
        return AppointmentResource::make($appointment)->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $appointment = $this->appointmentService->getAppointmentById($id);
        return AppointmentResource::make($appointment)->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentUpdateRequest $request, int $id)
    {
        $appointment = $this->appointmentService->updateAppointment($id, $request->validated());
        return AppointmentResource::make($appointment)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $this->appointmentService->deleteAppointment($appointment);
        return response()->json(['message' => 'Appointment deleted']);
    }

    /**
     * Get future appointments for a doctor.
     */
    public function futureAppointments(Request $request, int $doctorId): JsonResponse
    {
        $appointments = $this->appointmentService->getFutureAppointments($doctorId);
        return AppointmentResource::collection($appointments)->response();
    }
}
