<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Services\WhatsappService;

class WhatsappChannel
{

    protected $whatsappService;
    /**
     * Create a new channel instance.
     */
    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send the given notification.
     */
    public function send(User $notifiable, $notification): void
    {
        $message = $notification->toWhatsapp($notifiable);
        $this->whatsappService->sendMessage($notifiable->phone, $message['template'], $message['parameters']);
    }
}
