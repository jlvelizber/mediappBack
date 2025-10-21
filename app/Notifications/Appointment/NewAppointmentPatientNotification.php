<?php

namespace App\Notifications\Appointment;

use App\Broadcasting\WhatsappChannel;
use App\Enum\WayNotificationEnum;
use App\Models\Appointment;
use App\Traits\WayAppointmentNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppointmentPatientNotification extends Notification
{
    use Queueable, WayAppointmentNotificationTrait;

    protected $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment, string $wayNotification = WayNotificationEnum::BOTH->value)
    {
        $this->appointment = $appointment;
        $this->wayNotification = $wayNotification;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(config('app.name') . "-" . __('app.notifications.appointment_scheduled'))
            ->greeting(__('app.notifications.appointment_notification_greeting') . ' ' . $this->appointment->patient->name)
            ->line(__('app.notifications.appointment_patient_notification_line1', [
                'doctor' => $this->appointment->doctor->user->fullName,
                'specialization' => $this->appointment->doctor->specialization,
            ]))
            ->line(__('app.notifications.appointment_patient_notification_line2') . ' ' . $this->appointment->date_time)
            ->line(__('app.notifications.appointment_notification_thanks'));
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

    /**
     * Get the whatsapp representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toWhatsapp(object $notifiable): array
    {
        return [
            'template' => "appointment_confirmation",
            'parameters' => [
                $this->appointment->patient->name,
                $this->appointment->date_time,
                $this->appointment->date_time
            ],
        ];
    }
}
