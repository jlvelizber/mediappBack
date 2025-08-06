<?php

namespace App\Repositories\Eloquent;

use App\Models\Patient;
use App\Repositories\BaseRepository;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\Interface\PatientRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

class PatientRepository extends BaseRepository implements PatientRepositoryInterface
{
    protected AppointmentRepositoryInterface $appointmentRepositoryInterface;
    public function __construct(Patient $model, AppointmentRepositoryInterface $appointmentRepositoryInterface)
    {
        parent::__construct($model);
        $this->appointmentRepositoryInterface = $appointmentRepositoryInterface;
    }


    /**
     * Get patients by doctor id
     *
     * @param integer $doctorId
     * @return Collection
     */
    public function paginatePatientsByDoctorId(int $doctorId): LengthAwarePaginator
    {
        return $this->model->where('doctor_id', $doctorId)->paginate();
    }

    /**
     * Summary of queryPaginatePatientsByDoctorId
     * @param int $doctorId
     * @param string $query
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function queryPaginatePatientsByDoctorId(int $doctorId, string $query): LengthAwarePaginator
    {
        return $this->model->where('doctor_id', $doctorId)
            ->whereAny([
                'name',
                'lastname',
                'document',
                'email'
            ], 'like', "%{$query}%")
            ->paginate();
    }


    /**
     * Get patient by doctor id and patient id
     *
     * @param [type] $doctorId
     * @param [type] $id
     * @return Patient|null
     */
    public function getPatientByDoctorId($doctorId, $id): Patient|null
    {
        return $this->model->where('doctor_id', $doctorId)->where('id', $id)->first();
    }

    /**
     * Get all patients by doctor id
     *
     * @param [type] $doctorId
     * @return Collection
     */
    public function getAllPatientsByDoctorId($doctorId): Collection
    {
        return $this->model->where('doctor_id', $doctorId)->get();
    }

    /**
     * Get patient by appointment id
     *
     * @param int $doctorId
     * @param int $patientId
     * @return Patient
     */
    public function getPatientByAppointment($doctorId, $appointmentId): Patient|null
    {
        $appointment = $this->appointmentRepositoryInterface->find($appointmentId);
        if ($appointment) {
            return $appointment->patient()
                ->where('doctor_id', $doctorId)
                ->firstOrFail();
        }
        return null;
    }
}
