<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class AdminNewPublisherNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

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
            ->subject('Publisher Baru Mendaftar – ' . $this->publisher->name)
            ->greeting('Halo Admin,')
            ->line('Ada publisher baru yang baru saja mendaftar di LOA SIPTENAN dan memerlukan peninjauan Anda.')
            ->line('**Detail Pendaftaran Publisher:**')
            ->line('- Nama Publisher: ' . $this->publisher->name)
            ->line('- Email Pengguna: ' . $this->user->email)
            ->line('- Email Perusahaan: ' . $this->publisher->company_email)
            ->line('Silakan tinjau pendaftaran ini dan tentukan apakah akun publisher tersebut dapat diaktifkan.')
            ->action('Review Pendaftaran', url('/admin/publisher-validation'))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
