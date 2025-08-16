<?php

namespace App\Broadcasting;

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
    public function send($notifiable, $notification): void
    {
        $message = $notification->toWhatsapp($notifiable);

        if (!$message || !$notifiable->phone) {
            return;
        }

        $this->whatsappService->sendMessage($notifiable->phone, $message['template'], $message['parameters']);
    }
}
