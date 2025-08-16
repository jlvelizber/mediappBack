<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class PrescriptionReadyNotification extends Notification
{
    use Queueable;

    public string $path;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->generateUrlFromPath();
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('app.notifications.appointment_prescription_ready_subject'))
            ->line(__('app.notifications.appointment_prescription_ready'))
            ->action(__('app.notifications.appointment_download_pdf'), url($this->path))
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


    private function generateUrlFromPath(): void
    {
        // Assuming the path is a local storage path, convert it to a public URL
        $this->path = Storage::url($this->path);
    }
}
