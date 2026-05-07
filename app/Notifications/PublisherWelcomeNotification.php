<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PublisherWelcomeNotification extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $user,
        public $publisher
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
        return (new MailMessage)
            ->subject('Registrasi Publisher Berhasil – LOA SIPTENAN')
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line('Pendaftaran Anda sebagai publisher di LOA SIPTENAN telah berhasil diterima.')
            ->line('**Detail Publisher:**')
            ->line('- Nama Publisher: ' . $this->publisher->name)
            ->line('- Status: Menunggu Validasi')
            ->line('- Token Validasi: **' . $this->publisher->validation_token . '**')
            ->line('Akun publisher Anda saat ini sedang dalam proses peninjauan oleh tim admin kami. Anda akan menerima notifikasi lebih lanjut setelah proses validasi selesai.')
            ->line('Harap simpan token validasi Anda dengan aman karena akan diperlukan dalam proses selanjutnya.')
            ->action('Cek Status Akun', url('/publisher/validation/pending'))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
