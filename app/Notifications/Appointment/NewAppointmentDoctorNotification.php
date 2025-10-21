<?php

namespace App\Notifications\Appointment;

use App\Enum\WayNotificationEnum;
use App\Models\Appointment;
use App\Traits\WayAppointmentNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppointmentDoctorNotification extends Notification
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
            ->greeting(__('app.notifications.appointment_notification_greeting') . ' ' . $this->appointment->doctor->user->name)
            ->line(__('app.notifications.appointment_doctor_notification_line1', [
                'patient' => $this->appointment->patient->full_name,
            ]))
            ->line(__('app.notifications.appointment_doctor_notification_line2') . ' ' . $this->appointment->date_time)
            ->action(__('app.notifications.appointment_doctor_notification_action_line'), url(config('app.url') . '/appointments/' . $this->appointment->id))
            ->line(__('app.notifications.appointment_doctor_notification_thanks'));
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


    public function toWhatsapp(object $notifiable) : array
    {
        return [
            'template' => "appointment_confirmation_for_doctor",
            'parameters' => [
                $this->appointment->doctor->user->name,
                $this->appointment->patient->name,
                $this->appointment->date_time
            ],
        ];
    }
}
