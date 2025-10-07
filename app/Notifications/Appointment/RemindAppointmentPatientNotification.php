<?php

namespace App\Notifications\Appointment;

use App\Enum\WayNotificationEnum;
use App\Traits\WayAppointmentNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RemindAppointmentPatientNotification extends Notification implements ShouldQueue
{
    use Queueable, WayAppointmentNotificationTrait;


    /**
     * Create a new notification instance.
     */
    public function __construct(string $wayNotification = WayNotificationEnum::BOTH->value)
    {
        $this->wayNotification = $wayNotification;
    }


    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
