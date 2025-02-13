<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorAvailabilityStoreOwnRequest;
use App\Http\Requests\DoctorAvailabilityStoreRequest;
use App\Http\Requests\DoctorAvailabilityUpdateRequest;
use App\Http\Resources\DoctorAvailabilityResource;
use App\Services\DoctorAvailabilityService;
use Illuminate\Http\Request;

class DoctorAvailabilityController extends Controller
{

    protected DoctorAvailabilityService $doctorAvailabilityService;


    public function __construct(DoctorAvailabilityService $doctorAvailabilityService)
    {
        $this->doctorAvailabilityService = $doctorAvailabilityService;
    }


    /**
     * Summary of myAvailableTimes 
     * @param \Illuminate\Http\Client\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $doctorId = $request->user()->doctor->id;
        $availabitilies = $this->doctorAvailabilityService->getDoctorAvailability($doctorId);
        return DoctorAvailabilityResource::collection($availabitilies);
    }


    public function store(DoctorAvailabilityStoreOwnRequest $request)
    {
        $doctorId = $request->user()->doctor->id;
        $doctorAvailability = $this->doctorAvailabilityService->createAvailability(array_merge($request->all(), ['doctor_id' => $doctorId]));
        return new DoctorAvailabilityResource($doctorAvailability);
    }
}
