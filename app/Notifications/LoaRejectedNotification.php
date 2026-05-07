<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class LoaRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public $loaRequest
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
            ->to($this->loaRequest->author_email)
            ->subject('LOA Anda Ditolak – LOA SIPTENAN')
            ->greeting('Halo, ' . $this->loaRequest->author . '!')
            ->line('Kami menyampaikan bahwa permohonan Letter of Acceptance (LOA) Anda tidak dapat kami setujui pada saat ini.')
            ->line('**Detail Permohonan:**')
            ->line('- Judul Artikel: ' . $this->loaRequest->article_title);

        if ($this->loaRequest->admin_notes) {
            $mail->line('**Alasan Penolakan:**')
                 ->line($this->loaRequest->admin_notes);
        }

        return $mail
            ->line('Kami mendorong Anda untuk meninjau kembali permohonan Anda, melakukan perbaikan sesuai catatan di atas, dan mengajukan kembali permohonan LOA Anda.')
            ->line('Tim kami siap membantu jika Anda membutuhkan panduan lebih lanjut.')
            ->action('Ajukan Ulang', url('/loa/create'))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
