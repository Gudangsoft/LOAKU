<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class LoaApprovedNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->to($this->loaRequest->author_email)
            ->subject('LOA Anda Telah Disetujui – ' . $this->loaRequest->loa_code)
            ->greeting('Halo, ' . $this->loaRequest->author . '!')
            ->line('Selamat! Permohonan Letter of Acceptance (LOA) Anda telah disetujui oleh tim LOA SIPTENAN.')
            ->line('**Detail LOA:**')
            ->line('- Judul Artikel: ' . $this->loaRequest->article_title)
            ->line('- Nama Jurnal: ' . $this->loaRequest->journal_name)
            ->line('- Kode LOA: **' . $this->loaRequest->loa_code . '**')
            ->line('Anda dapat mengunduh dokumen LOA Anda melalui tombol di bawah ini menggunakan kode LOA Anda.')
            ->action('Unduh LOA', url('/search-loa?code=' . $this->loaRequest->loa_code))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
