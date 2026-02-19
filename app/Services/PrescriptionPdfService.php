<?php

namespace App\Services;

use App\Events\PrescriptionReadyEvent;
use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PrescriptionPdfService
{
    /**
     * Genera el PDF de la prescripción, lo guarda en storage y opcionalmente dispara PrescriptionReadyEvent
     * (envío por correo al paciente). Si $notifyPatient es false (ej. descarga por el médico), no se emite el evento.
     * Devuelve la ruta del archivo y el contenido del PDF para poder devolverlo en la respuesta HTTP.
     *
     * @param  bool  $notifyPatient  Si es true, emite PrescriptionReadyEvent para enviar el PDF por correo al paciente.
     * @return array{path: string, output: string, filename: string}
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function generateAndStore(Prescription $prescription, bool $notifyPatient = true): array
    {
        $prescription->loadMissing([
            'items',
            'appointment.patient',
            'appointment.doctor.user',
            'appointment.doctor.configuration',
        ]);

        $patient = $prescription->appointment->patient;
        $doctor = $prescription->appointment->doctor;

        if (!$patient || !$doctor) {
            Log::warning("Prescription (ID: {$prescription->id}) is missing patient or doctor information.");
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
                'Faltan datos de paciente o médico para generar la receta.'
            );
        }

        $config = $doctor->configuration;
        if (!$config) {
            Log::warning("Doctor (ID: {$doctor->id}) has no configuration set.");
            $clinicName = config('app.name', 'MediApp');
            $clinicAddress = $clinicPhone = $clinicEmail = '';
        } else {
            $clinicName = $config->medical_center_name ?? config('app.name', 'MediApp');
            $clinicAddress = $config->medical_center_address ?? '';
            $clinicPhone = $config->medical_center_phone ?? '';
            $clinicEmail = $config->medical_center_email ?? '';
        }

        $pdf = Pdf::loadView('pdf.prescription', [
            'prescription' => $prescription,
            'clinicName' => $clinicName,
            'clinicAddress' => $clinicAddress,
            'clinicPhone' => $clinicPhone,
            'clinicEmail' => $clinicEmail,
        ]);

        $output = $pdf->output();
        $now = now()->format('Ymd_His');
        $patientName = strtolower(str_replace(' ', '_', $patient->full_name));
        $path = "prescriptions/prescription_{$patientName}_{$now}_{$prescription->id}.pdf";
        Storage::disk('public')->put($path, $output);

        if ($notifyPatient) {
            event(new PrescriptionReadyEvent($prescription, $path));
        }

        $filename = "receta_{$patientName}_{$now}.pdf";

        return [
            'path' => $path,
            'output' => $output,
            'filename' => $filename,
        ];
    }
}
