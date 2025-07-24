<?php

namespace App\Http\Controllers\Doctor;

use App\Events\AppointmentCreated;
use App\Http\Requests\Common\AppointmentChangeStatusRequest;
use App\Http\Requests\Doctor\Appointment\{DoctorAppointmentStoreRequest, DoctorAppointmentUpdateRequest};
use App\Http\Resources\AppointmentPaginateResource;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use Exception;
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
    public function index(Request $request): JsonResponse
    {
        //  vamos a filtrar por fecha si se envían los parámetros start_date y end_date
        if ($request->has('start_date') && $request->has('end_date')) {
            $doctorId = $request->user()->doctor->id;
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $appointments = $this->appointmentService->getAppointmentsByDateRange($doctorId, $startDate, $endDate);
            return AppointmentPaginateResource::collection($appointments)->response();
        }


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
        $doctorId = $request->user()->doctor->id;
        $request->merge(['doctor_id' => $doctorId]);
        $appointment = $this->appointmentService->updateAppointment($id, $request->all());
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

    /**
     * Update the status of an appointment.
     */
    public function updateStatus(AppointmentChangeStatusRequest $request, int $appointment): JsonResponse
    {
        $status = $request->input('status');
        $wasOk = false;
        try {
            $appointment = $this->appointmentService->updateAppointmentStatus($appointment, $status);
            if ($appointment) {
                $wasOk = true;
            }

        } catch (Exception $th) {
            $wasOk = false;
        } finally {
            return response()->json([
                'was_success' => $wasOk
            ]);
        }
    }
}
