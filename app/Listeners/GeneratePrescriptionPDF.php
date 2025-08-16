<?php

namespace App\Listeners;

use App\Events\MedicalRecordCreatedEvent;
use App\Events\PrescriptionReadyEvent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeneratePrescriptionPDF implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MedicalRecordCreatedEvent $event): void
    {
        $prescription = $event->medicalRecord->appointment?->prescription;
        if (!$prescription) {
            Log::warning("Medical Record (ID: {$event->medicalRecord->id}) has no associated prescription.");
            return; // No prescription associated with the medical record
        }

        $patient = $prescription->appointment->patient;
        $doctor = $prescription->appointment->doctor;
        if (!$patient || !$doctor) {
            Log::warning("Prescription (ID: {$prescription->id}) is missing patient or doctor information.");
            return; // Ensure both patient and doctor are available
        }
        $config = $doctor->configuration;

        if (!$config) {
            Log::warning("Doctor (ID: {$doctor->id}) has no configuration set.");
            return; // Ensure doctor configuration exists
        }

        $clinicName = $config->medical_center_name ?? config('app.name', 'MediApp');
        $clinicAddress = $config->medical_center_address;
        $clinicPhone = $config->medical_center_phone;
        $clinicEmail = $config->medical_center_email;


        // Logic to generate PDF for the prescription
        // This could involve using a PDF generation library to create a PDF file
        // and save it to a specific location or send it via email/notification.
        // Example: Generate PDF and save it
        $now = now()->format('Ymd_His');
        $patientName = strtolower(str_replace(' ', '_', $patient->full_name));
        $pdf = Pdf::loadView(
            'pdf.prescription',
            [
                'prescription' => $prescription,
                'clinicName' => $clinicName,
                'clinicAddress' => $clinicAddress,
                'clinicPhone' => $clinicPhone,
                'clinicEmail' => $clinicEmail,
            ]
        );
        $path = "prescriptions/prescription_{$patientName}_{$now}_{$prescription->id}.pdf";
        Storage::disk('public')->put($path, $pdf->output());

        event(new PrescriptionReadyEvent($prescription, $path));


    }
}
