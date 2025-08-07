<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\MedicalRecord\UpdateMedicalRecordlHistoryRequest;
use App\Http\Requests\Doctor\MedicalRecord\StoreMedicalRecordRequest;
use App\Http\Resources\AppointmentMedicalRecordStoredResource;
use App\Http\Resources\MedicalRecordResource;
use App\Services\MedicalRecordService;
use Illuminate\Http\JsonResponse;

class DoctorMedicalRecordController extends Controller
{

    protected MedicalRecordService $medicalRecordService;

    public function __construct(medicalRecordService $medicalRecordService)
    {
        $this->medicalRecordService = $medicalRecordService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $histories = $this->medicalRecordService->getAllClinicalHistories();
        return MedicalRecordResource::collection($histories)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicalRecordRequest $request)
    {
        $medicalRecord = $this->medicalRecordService->createMedicalRecord($request->all());
        return AppointmentMedicalRecordStoredResource::make($medicalRecord)->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $clinicalHistory = $this->medicalRecordService->getClinicalHistoryByAppointmentId((int) $id);
        return MedicalRecordResource::make($clinicalHistory)->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicalRecordlHistoryRequest $request, string $id)
    {
        $clinicalHistory = $this->medicalRecordService->updateClinicalHistory((int) $id, $request->all());
        return MedicalRecordResource::make($clinicalHistory)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->medicalRecordService->deleteClinicalHistory($id);
        return response()->json(['message' => 'Clinical history deleted']);
    }
}
