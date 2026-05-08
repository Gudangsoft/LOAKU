<?php

namespace App\Notifications;

use App\Models\SubscriptionPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class SubscriptionProofReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public SubscriptionPayment $payment) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $payment   = $this->payment;
        $publisher = $payment->publisher;

        return (new MailMessage)
            ->subject("Bukti Bayar Masuk – {$publisher->name} [{$payment->invoice_number}]")
            ->greeting('Halo, Admin!')
            ->line("Publisher **{$publisher->name}** telah mengupload bukti pembayaran.")
            ->line("- No. Invoice : {$payment->invoice_number}")
            ->line("- Paket       : {$payment->plan->name}")
            ->line("- Nominal     : Rp " . number_format($payment->amount, 0, ',', '.'))
            ->line('Silakan periksa bukti pembayaran dan konfirmasi atau tolak.')
            ->action('Periksa Sekarang', url("/admin/subscription-payments/{$payment->id}"))
            ->salutation("Salam,\nSistem LOA SIPTENAN");
    }
}
