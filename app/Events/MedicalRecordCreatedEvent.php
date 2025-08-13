<?php

namespace App\Events;

use App\Models\MedicalRecord;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MedicalRecordCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The medical record instance.
     *
     * @var \App\Models\MedicalRecord
     */
    public $medicalRecord;

    /**
     * Create a new event instance.
     */
    public function __construct(MedicalRecord $medicalRecord)
    {
        $this->medicalRecord = $medicalRecord;
    }
}
