<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class PublisherActivatedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $publisher,
        public ?string $notes = null
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
            ->subject('Akun Publisher Anda Telah Diaktifkan – LOA SIPTENAN')
            ->greeting('Selamat, ' . $this->publisher->name . '!')
            ->line('Akun publisher Anda di LOA SIPTENAN telah resmi diaktifkan oleh admin kami.')
            ->line('**Token Validasi Anda:** ' . $this->publisher->validation_token)
            ->line('Gunakan token validasi ini untuk mengautentikasi akun publisher Anda saat diperlukan. Harap simpan token ini dengan aman dan jangan bagikan kepada pihak lain.')
            ->line('Anda kini dapat login ke dashboard publisher dan mulai menggunakan layanan LOA SIPTENAN.');

        if ($this->notes) {
            $mail->line('**Catatan dari Admin:**')
                 ->line($this->notes);
        }

        return $mail
            ->action('Login ke Dashboard', url('/publisher/dashboard'))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
