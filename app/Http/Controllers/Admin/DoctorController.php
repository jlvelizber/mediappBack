<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DoctorStoreRequest;
use App\Http\Requests\Admin\DoctorUpdateRequest;
use App\Http\Resources\DoctorResource;
use App\Services\DoctorService;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{

    protected DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $doctors = $this->doctorService->getAllDoctors();
        return DoctorResource::collection($doctors)->response();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorStoreRequest $request): JsonResponse
    {
        $doctor = $this->doctorService->createDoctor($request->all());
        return DoctorResource::make($doctor)->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $doctor): JsonResponse
    {
        $doctor = $this->doctorService->getDoctorById($doctor);
        return DoctorResource::make($doctor)->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorUpdateRequest $request, int $doctor)
    {
        $doctor = $this->doctorService->updateDoctor($doctor, $request->all());
        return DoctorResource::make($doctor)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $doctor)
    {
        $this->doctorService->deleteDoctor($doctor);
        return response()->json(['message' => 'Doctor deleted successfully']);
    }
}
