<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorAvailabilityStoreRequest;
use App\Http\Requests\DoctorAvailabilityUpdateRequest;
use App\Http\Resources\DoctorAvailabilityResource;
use App\Services\DoctorAvailabilityService;

class DoctorAvailabilityController extends Controller
{

    protected DoctorAvailabilityService $doctorAvailabilityService;


    public function __construct(DoctorAvailabilityService $doctorAvailabilityService)
    {
        $this->doctorAvailabilityService = $doctorAvailabilityService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(int $doctorId)
    {
        $availabitilies = $this->doctorAvailabilityService->getDoctorAvailability($doctorId);
        return DoctorAvailabilityResource::collection($availabitilies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DoctorAvailabilityStoreRequest $request)
    {
        $doctorAvailability = $this->doctorAvailabilityService->createAvailability($request->all());
        return new DoctorAvailabilityResource($doctorAvailability);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DoctorAvailabilityUpdateRequest $request, int $doctorId, int $doctorAvailability)
    {
        $doctorAvailability = $this->doctorAvailabilityService->updateAvailability($doctorAvailability, $request->all());
        return new DoctorAvailabilityResource($doctorAvailability);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $doctorId, int $doctorAvailability)
    {
        $this->doctorAvailabilityService->deleteAvailability($doctorAvailability);
        return response()->json(['message' => 'Doctor Availability deleted']);
    }
}
