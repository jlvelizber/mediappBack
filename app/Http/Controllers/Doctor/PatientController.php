<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\Patient\PatientStoreRequest as DoctorPatientStoreRequest;
use App\Http\Requests\Doctor\Patient\PatientUpdateRequest as DoctorPatientUpdateRequest;
use App\Http\Resources\PatientPaginateResource;
use App\Http\Resources\PatientResource;
use App\Services\PatientService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{

    protected PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $doctorId = $request->user()->doctor->id;
        $patients = $this->patientService->getAllPatientsByDoctorId($doctorId);
        return PatientResource::collection($patients)->response();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorPatientStoreRequest $request): JsonResponse
    {
        $doctorId = $request->user()->doctor->id;
        $request->merge(['doctor_id' => $doctorId]);
        $patient = $this->patientService->createPatient($request->all());
        return PatientResource::make($patient)->response();

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $patient)
    {
        $doctorId = $request->user()->doctor->id;
        $patient = $this->patientService->getPatientByDoctorId($doctorId, $patient);
        return PatientResource::make($patient)->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorPatientUpdateRequest $request, int $patient)
    {
        $doctorId = $request->user()->doctor->id;
        $request->merge(['doctor_id' => $doctorId]);
        $patient = $this->patientService->updatePatientByDoctorId($patient, $request->all());
        return PatientResource::make($patient)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $patient)
    {
        $doctorId = $request->user()->doctor->id;
        $this->patientService->deletePatientByDoctorId($doctorId, $patient);
        return response()->json(['message' => 'Patient deleted successfully']);
    }


    public function paginate(Request $request): JsonResponse
    {
        $doctorId = $request->user()->doctor->id;
        if ($request->has('query')) {
            $query = $request->get('query');
            $patients = $this->patientService->queryPaginatePatientByDoctorId($doctorId, $query);
        } else {
            $patients = $this->patientService->paginatePatientByDoctorId($doctorId);
        }
        return PatientPaginateResource::collection($patients)->response();
    }


    /**
     * Get patient by appointment.
     */
    public function getPatientByAppointment(Request $request, int $appointmentId): JsonResponse
    {
        $doctorId = $request->user()->doctor->id;
        $patient = $this->patientService->getPatientByAppointment($doctorId, $appointmentId);
        return PatientResource::make($patient)->response();
    }
}
