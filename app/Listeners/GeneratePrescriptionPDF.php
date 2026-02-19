<?php

namespace App\Listeners;

use App\Events\MedicalRecordCreatedEvent;
use App\Services\PrescriptionPdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class GeneratePrescriptionPDF implements ShouldQueue
{
    public function __construct(
        protected PrescriptionPdfService $prescriptionPdfService
    ) {
    }

    /**
     * Genera el PDF de la prescripción, lo guarda y dispara el evento (envío por correo al paciente).
     */
    public function handle(MedicalRecordCreatedEvent $event): void
    {
        $prescription = $event->medicalRecord->appointment?->prescription;

        if (!$prescription) {
            Log::warning("Medical Record (ID: {$event->medicalRecord->id}) has no associated prescription.");
            return;
        }

        try {
            $this->prescriptionPdfService->generateAndStore($prescription);
        } catch (\Throwable $e) {
            Log::error("GeneratePrescriptionPDF failed for prescription ID {$prescription->id}: " . $e->getMessage());
            throw $e;
        }
    }
}
