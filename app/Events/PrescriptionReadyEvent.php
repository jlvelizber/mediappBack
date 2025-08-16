<?php

namespace App\Events;

use App\Models\Prescription;
use DragonCode\Contracts\Queue\ShouldQueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrescriptionReadyEvent implements ShouldQueue
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The name of the event.
     */
    public string $eventName = 'prescription.ready';

    /**
     * The prescription instance.
     */


    public Prescription $prescription;
    public string $path;

    /**
     * Create a new event instance.
     */
    public function __construct(Prescription $prescription, string $path)
    {
        $this->prescription = $prescription;
        $this->path = $path;
    }
}
