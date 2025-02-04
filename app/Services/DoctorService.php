<?php

namespace App\Services;


use App\Models\Doctor;
use App\Repositories\Interface\DoctorRepositoryInterface;
use App\Repositories\Interface\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctorService
{
    protected DoctorRepositoryInterface $doctorRepositoryInterface;
    protected UserRepositoryInterface $userRepositoryInterface;

    public function __construct(
        DoctorRepositoryInterface $doctorRepositoryInterface,
        UserRepositoryInterface $userRepositoryInterface
    ) {
        $this->doctorRepositoryInterface = $doctorRepositoryInterface;
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function getAllDoctors(): Collection
    {
        return $this->doctorRepositoryInterface->all();
    }

    public function getDoctorById($id): Doctor|null
    {
        $doctor = $this->doctorRepositoryInterface->find($id);
        if (!$doctor)
            throw new NotFoundHttpException("Doctor not found", null, Response::HTTP_NOT_FOUND);
        return $doctor;
    }

    public function createDoctor(array $data): Doctor
    {
        $user = $this->userRepositoryInterface->create($data);
        $data['user_id'] = $user->id;
        $doctor = $this->doctorRepositoryInterface->create($data);
        $doctor->load('user');
        return $doctor;

    }

    public function updateDoctor($id, array $data): Doctor|null
    {
        $doctor = $this->getDoctorById($id);
        $this->userRepositoryInterface->update($doctor->user_id, $data);
        $doctorWasUpdated = $this->doctorRepositoryInterface->update($id, $data);
        if (!$doctorWasUpdated)
            throw ValidationException::withMessages(['doctor' => 'Doctor was not updated']);
        $doctor = $this->getDoctorById($id);
        $doctor->load('user');
        return $doctor;
    }

    public function deleteDoctor($id)
    {
        $doctor = $this->getDoctorById($id);
        $this->userRepositoryInterface->delete($doctor->user_id);
        return $this->doctorRepositoryInterface->delete($id);
    }
}
