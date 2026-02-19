<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\PrescriptionPdfService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctorPrescriptionController extends Controller
{
    public function __construct(
        protected PrescriptionPdfService $prescriptionPdfService
    ) {
    }

    /**
     * Genera el PDF de la prescripción, lo guarda y devuelve el archivo para descarga.
     * No envía correo al paciente (solo descarga); el envío por correo ocurre cuando la cita se completa.
     */
    public function download(Request $request, Appointment $appointment): StreamedResponse
    {
        $doctorId = $request->user()->doctor?->id;
        if (!$doctorId || (int) $appointment->doctor_id !== (int) $doctorId) {
            abort(403, 'No autorizado para esta prescripción.');
        }

        $prescription = $appointment->prescription;
        if (!$prescription) {
            throw new NotFoundHttpException('Esta cita no tiene prescripción.');
        }

        $result = $this->prescriptionPdfService->generateAndStore($prescription, notifyPatient: false);

        return response()->streamDownload(
            fn () => print($result['output']),
            $result['filename'],
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $result['filename'] . '"',
            ]
        );
    }
}
