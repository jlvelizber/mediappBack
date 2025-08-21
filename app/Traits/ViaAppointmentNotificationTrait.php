<?php
namespace App\Traits;

use App\Broadcasting\WhatsappChannel;
use App\Enum\WayNotificationEnum;

/**
 * Ayuda a detectar la via en la forma en que se enviara las notificaciones
 */
trait ViaAppointmentNotificationTrait
{

    private string $wayNotification;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $via = match ($this->wayNotification) {
            WayNotificationEnum::BOTH->value => ['database', WhatsappChannel::class, 'mail'],
            WayNotificationEnum::WHATSAPP->value => [WhatsappChannel::class],
            default => ['database', 'mail'],
        };

        return $via;

    }

}
