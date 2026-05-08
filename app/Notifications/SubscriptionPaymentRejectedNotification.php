<?php

namespace App\Notifications;

use App\Models\SubscriptionPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class SubscriptionPaymentRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public SubscriptionPayment $payment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $payment = $this->payment;

        return (new MailMessage)
            ->subject("Pembayaran Ditolak – LOA SIPTENAN")
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Maaf, bukti pembayaran Anda **ditolak** oleh tim admin.')
            ->line("- No. Invoice : {$payment->invoice_number}")
            ->line("- Paket       : {$payment->plan->name}")
            ->when($payment->admin_notes, fn($m) => $m->line("- Alasan      : {$payment->admin_notes}"))
            ->line('Silakan upload ulang bukti pembayaran yang benar melalui dashboard publisher Anda.')
            ->action('Upload Ulang', url('/publisher/subscription'))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
