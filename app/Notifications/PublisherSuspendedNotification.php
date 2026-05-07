<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class PublisherSuspendedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $publisher,
        public ?string $reason = null
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Akun Publisher Anda Disuspend – LOA SIPTENAN')
            ->greeting('Kepada ' . $this->publisher->name . ',')
            ->line('Kami memberitahukan bahwa akun publisher Anda di LOA SIPTENAN telah disuspend oleh tim admin kami.');

        if ($this->reason) {
            $mail->line('**Alasan Suspensi:**')
                 ->line($this->reason);
        } else {
            $mail->line('Akun Anda disuspend karena melanggar ketentuan layanan LOA SIPTENAN.');
        }

        return $mail
            ->line('Jika Anda merasa keputusan ini kurang tepat atau ingin mendapatkan klarifikasi lebih lanjut, silakan hubungi tim admin kami melalui email atau saluran komunikasi resmi LOA SIPTENAN.')
            ->line('Kami akan dengan senang hati meninjau kembali situasi Anda.')
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
