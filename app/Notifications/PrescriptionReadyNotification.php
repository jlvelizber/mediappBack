<?php

namespace App\Notifications;

use App\Broadcasting\WhatsappChannel;
use App\Enum\WayNotificationEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PrescriptionReadyNotification extends Notification
{
    use Queueable;

    public string $path;

    public string $wayNotification;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $pathFile, string $wayNotification = WayNotificationEnum::BOTH->value)
    {
        $this->path = $pathFile;
        $this->wayNotification = $wayNotification;
        $this->generateUrlFromPath();
    }


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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::info('Sending prescription ready notification to patient', [
            'patient_id' => $notifiable->id,
            'path' => $this->path,
        ]);
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

    public function toWhatsapp(object $notifiable): array
    {
        Log::info('Sending prescription ready notification to patient via WhatsApp', [
            'patient_id' => $notifiable->id,
            'path' => $this->path,
        ]);
        return [
            'template' => 'prescription_ready_es',
            'parameters' => [
                __('app.notifications.appointment_prescription_ready'),
                url($this->path),
            ],
        ];
    }
}
