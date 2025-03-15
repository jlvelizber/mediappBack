<?php

namespace App\Services;

use App\Enum\AppointmentStatusEnum;
use App\Enum\DaysWeekEnum;
use App\Models\DoctorAvailability;
use App\Repositories\Interface\AppointmentRepositoryInterface;
use App\Repositories\Interface\DoctorAvailabilityRepositoryInterface;
use App\Repositories\Interface\DoctorConfigurationRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctorAvailabilityService
{
    protected DoctorAvailabilityRepositoryInterface $doctorAvailabilityRepositoryInterface;
    protected AppointmentRepositoryInterface $appointmentRepositoryInterface;
    protected DoctorConfigurationRepositoryInterface $doctorConfigurationRepositoryInterface;

    public function __construct(
        DoctorAvailabilityRepositoryInterface $doctorAvailabilityRepositoryInterface,
        AppointmentRepositoryInterface $appointmentRepositoryInterface,
        DoctorConfigurationRepositoryInterface $doctorConfigurationRepositoryInterface
    ) {
        $this->doctorAvailabilityRepositoryInterface = $doctorAvailabilityRepositoryInterface;
        $this->appointmentRepositoryInterface = $appointmentRepositoryInterface;
        $this->doctorConfigurationRepositoryInterface = $doctorConfigurationRepositoryInterface;
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

    /**
     * get Slots available for a doctor
     * @param int $doctorId
     */
    public function getAvailableSlots(int $doctorId, string $date): Collection
    {
        $date = Carbon::parse($date);
        $dayOfWeek = DaysWeekEnum::getKeyByIndex($date->dayOfWeek);

        $availability = $this->doctorAvailabilityRepositoryInterface->getFirstByDoctorAndDay($doctorId, $dayOfWeek);

        if (!$availability)
            return new Collection([]);

        $appointments = $this->appointmentRepositoryInterface->getDoctorAppointmentsByDate($doctorId, $date)
            ->pluck('date_time')
            ->toArray();
        // dd($appointments);
        $duration = $this->doctorConfigurationRepositoryInterface->getByDoctorIdAndKeyValue($doctorId, 'default_appointment_duration')->default_appointment_duration ?? config('mediapp.appointment.default_duration_minutes');

        // Definir el rango de horarios según la disponibilidad
        $dateTradition = $date->format('Y-m-d');
        $startTime = Carbon::parse("$dateTradition $availability->start_time");
        $endTime = Carbon::parse("$dateTradition $availability->end_time");

        $availableSlots = [];
        while ($startTime->lt($endTime)) {
            if (!in_array($startTime->toDateTimeString(), $appointments)) {
                $availableSlots[]['hour'] = $startTime->format('H:i');
            }
            $startTime->addMinutes((int) $duration);
        }

        return new Collection($availableSlots);

    }
}
