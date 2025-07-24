<?php

namespace App\Http\Controllers;

use App\Http\Requests\Doctor\ClinicalHistory\StoreClinicalHistoryRequest;
use App\Http\Requests\Doctor\ClinicalHistory\UpdateClinicalHistoryRequest;
use App\Http\Resources\ClinicalHistoryResource;
use App\Services\ClinicalHistoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClinicalHistoryController extends Controller
{

    protected ClinicalHistoryService $clinicalHistoryService;

    public function __construct(ClinicalHistoryService $clinicalHistoryService)
    {
        $this->clinicalHistoryService = $clinicalHistoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $histories = $this->clinicalHistoryService->getAllClinicalHistories();
        return ClinicalHistoryResource::collection($histories)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClinicalHistoryRequest $request)
    {
        $clinicalHistory = $this->clinicalHistoryService->createClinicalHistory($request->all());
        return ClinicalHistoryResource::make($clinicalHistory)->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $clinicalHistory = $this->clinicalHistoryService->getClinicalHistoryByAppointmentId((int) $id);
        return ClinicalHistoryResource::make($clinicalHistory)->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClinicalHistoryRequest $request, string $id)
    {
        $clinicalHistory = $this->clinicalHistoryService->updateClinicalHistory((int) $id, $request->all());
        return ClinicalHistoryResource::make($clinicalHistory)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->clinicalHistoryService->deleteClinicalHistory($id);
        return response()->json(['message' => 'Clinical history deleted']);
    }
}
