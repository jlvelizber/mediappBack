<?php

namespace App\Http\Controllers;

use App\Http\Requests\Doctor\DoctorStoreRequest;
use App\Http\Requests\Doctor\DoctorUpdateRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Services\DoctorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function store(DoctorStoreRequest $request)
    {
        $doctor = $this->doctorService->createDoctor($request->all());
        return DoctorResource::make($doctor)->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $doctor)
    {
        $doctor = $this->doctorService->getDoctorById($doctor);
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
