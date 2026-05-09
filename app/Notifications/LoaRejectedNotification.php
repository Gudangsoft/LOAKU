<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoaRejectedNotification extends Notification
{
    public function __construct(public $loaRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

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
            ->line('Kami mendorong Anda untuk meninjau kembali permohonan Anda dan mengajukan kembali.')
            ->action('Ajukan Ulang', url('/loa/create'))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'  => 'loa_rejected',
            'title' => 'LOA Ditolak',
            'body'  => 'LOA untuk artikel "' . $this->loaRequest->article_title . '" ditolak.' . ($this->loaRequest->admin_notes ? ' Alasan: ' . $this->loaRequest->admin_notes : ''),
            'url'   => url('/publisher/loa-requests'),
        ];
    }
}
