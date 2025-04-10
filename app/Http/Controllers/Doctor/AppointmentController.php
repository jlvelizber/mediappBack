<?php

namespace App\Http\Controllers\Doctor;

use App\Events\AppointmentCreated;
use App\Http\Requests\Doctor\Appointment\{DoctorAppointmentStoreRequest, DoctorAppointmentUpdateRequest};
use App\Http\Resources\AppointmentPaginateResource;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    public function store(DoctorAppointmentStoreRequest $request)
    {
        $appointment = $this->appointmentService->createAppointment($request->all());
        event(new AppointmentCreated($appointment));
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
    public function update(DoctorAppointmentUpdateRequest $request, int $id)
    {
        $appointment = $this->appointmentService->updateAppointment($id, $request->validated());
        return AppointmentResource::make($appointment)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $appointment)
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

    /**
     * Get past appointments for a doctor.
     */
    public function paginate(Request $request): JsonResponse
    {
        $doctorId = $request->user()->doctor->id;
        if ($request->has('query')) {
            $query = $request->get('query');
            $appointments = $this->appointmentService->queryPaginateAppointmentByDoctorId($doctorId, $query);
        } else {
            $appointments = $this->appointmentService->paginateAppointmentsByDoctor($doctorId);
        }
        return AppointmentPaginateResource::collection($appointments)->response();
    }
}
