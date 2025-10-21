<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use App\Services\PatientService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{   
    private AppointmentService $appointmentService;

    private PatientService $patientService;

    public function __construct(
        AppointmentService $appointmentService,
        PatientService $patientService
    )
    {
        $this->appointmentService = $appointmentService;
        $this->patientService = $patientService;
    }
    public function index(Request $request)
    {
        $doctorId = $request->user()->doctor->id;
        $startDate = Carbon::today()->format('Y-m-d');
        $endDate = $startDate;
        

        // today appointments
        $todayAppointments = $this->appointmentService->getAppointmentsByDateRange($doctorId, $startDate, $endDate);
        // total patients by doctor
        $totalPatients = $this->patientService->getTotalPatientsByDoctorId($doctorId);

        //total Appointments weekly
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $weeklyStats = $this->appointmentService->getWeeklyResume($doctorId, $weekStart, $weekEnd);


        //Proxima cita
        $nextAppointment =  $this->appointmentService->getFutureAppointments($doctorId);

        return response()->json([
            'todayAppointments' => $todayAppointments->count(),
            'totalPatients'=> $totalPatients,
            'weeklyStats' => $weeklyStats,
            'nextAppointment'=> $nextAppointment
        ]);
    }
}
