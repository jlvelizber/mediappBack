<?php

namespace App\Services;

use App\Models\DoctorAvailability;
use App\Repositories\Interface\DoctorAvailabilityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctorAvailabilityService
{
    protected DoctorAvailabilityRepositoryInterface $doctorAvailabilityRepositoryInterface;

    public function __construct(DoctorAvailabilityRepositoryInterface $doctorAvailabilityRepositoryInterface)
    {
        $this->doctorAvailabilityRepositoryInterface = $doctorAvailabilityRepositoryInterface;
    }

    /**
     * Summary of getDoctorAvailability
     * @param int $doctorId
     * @return Collection|null
     */
    public function getDoctorAvailability(int $doctorId): ?Collection
    {
        return $this->doctorAvailabilityRepositoryInterface->getByDoctor($doctorId);
    }

    public function getDoctorAvailabilityById($id): DoctorAvailability|null
    {
        $availability = $this->doctorAvailabilityRepositoryInterface->find($id);
        if (!$availability)
            throw new NotFoundHttpException("Doctor Availability not found", null, Response::HTTP_NOT_FOUND);
        return $availability;
    }


    public function createAvailability(array $data): DoctorAvailability
    {
        return $this->doctorAvailabilityRepositoryInterface->create($data);
    }

    public function updateAvailability($availability, array $data)
    {
        $this->getDoctorAvailabilityById($availability);
        $updated = $this->doctorAvailabilityRepositoryInterface->update($availability, $data);
        if (!$updated)
            throw ValidationException::withMessages(['doctor' => 'Doctor Availability was not updated']);

        return $this->getDoctorAvailabilityById($availability);
    }

    public function deleteAvailability($availability)
    {
        $this->getDoctorAvailabilityById($availability);
        return $this->doctorAvailabilityRepositoryInterface->delete($availability);
    }
}
