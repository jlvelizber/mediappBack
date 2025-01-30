<?php

namespace App\Http\Controllers;

use App\Http\Requests\Patient\PatientStoreRequest;
use App\Http\Requests\Patient\PatientUpdateRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Services\PatientService;
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
    public function index(): JsonResponse
    {
        $patients = $this->patientService->getAllPatients();
        return PatientResource::collection($patients)->response();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientStoreRequest $request): JsonResponse
    {
        $patient = $this->patientService->createPatient($request->all());
        return PatientResource::make($patient)->response();

    }

    /**
     * Display the specified resource.
     */
    public function show(int $patient)
    {
        $patient = $this->patientService->getPatientById($patient);
        return PatientResource::make($patient)->response();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PatientUpdateRequest $request, int $patient)
    {
        $patient = $this->patientService->updatePatient($patient, $request->all());
        return PatientResource::make($patient)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $patient)
    {
        $this->patientService->deletePatient($patient);
        return response()->json(['message' => 'Patient deleted successfully']);
    }
}
