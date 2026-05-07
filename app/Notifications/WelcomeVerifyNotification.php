<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class WelcomeVerifyNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $user,
        public string $verificationUrl
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
            ->subject('Verifikasi Email Anda – LOA SIPTENAN')
            ->greeting('Halo, ' . $this->user->name . '!')
            ->line('Selamat datang di LOA SIPTENAN (loa.siptenan.org)!')
            ->line('Terima kasih telah mendaftar. Untuk mengaktifkan akun Anda, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini.')
            ->line('Tanpa verifikasi, akun Anda tidak dapat diaktifkan dan Anda tidak akan dapat menggunakan layanan kami.')
            ->action('Verifikasi Email Sekarang', $this->verificationUrl)
            ->line('Jika Anda tidak mendaftar, abaikan email ini.')
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
