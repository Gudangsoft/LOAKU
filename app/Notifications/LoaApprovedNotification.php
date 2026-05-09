<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LoaApprovedNotification extends Notification
{
    public function __construct(public $loaRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loaCode = $this->loaRequest->loa_code
            ?? optional($this->loaRequest->loaValidated)->loa_code
            ?? '-';
        $journalName = optional($this->loaRequest->journal)->name ?? '-';

        return (new MailMessage)
            ->to($this->loaRequest->author_email)
            ->subject('LOA Anda Telah Disetujui – ' . $loaCode)
            ->greeting('Halo, ' . $this->loaRequest->author . '!')
            ->line('Selamat! Permohonan Letter of Acceptance (LOA) Anda telah disetujui oleh tim ' . site_name() . '.')
            ->line('**Detail LOA:**')
            ->line('- Judul Artikel: ' . $this->loaRequest->article_title)
            ->line('- Nama Jurnal: ' . $journalName)
            ->line('- Kode LOA: **' . $loaCode . '**')
            ->line('Anda dapat mengunduh dokumen LOA Anda melalui tombol di bawah ini menggunakan kode LOA Anda.')
            ->action('Unduh LOA', route('loa.verify.result', $loaCode))
            ->salutation("Salam,\nTim " . site_name());
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'loa_approved',
            'title'   => 'LOA Disetujui',
            'body'    => 'LOA untuk artikel "' . $this->loaRequest->article_title . '" telah disetujui. Kode: ' . $this->loaRequest->loa_code,
            'loa_code'=> $this->loaRequest->loa_code,
            'url'     => url('/search-loa?code=' . $this->loaRequest->loa_code),
        ];
    }
}
