<?php

namespace App\Notifications;

use App\Models\SubscriptionPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class SubscriptionPaymentConfirmedNotification extends Notification implements ShouldQueue
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
        $sub     = $payment->publisher->activeSubscription;

        return (new MailMessage)
            ->subject("Pembayaran Dikonfirmasi – LOA SIPTENAN")
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Pembayaran Anda telah **dikonfirmasi** oleh tim admin.')
            ->line("- No. Invoice : {$payment->invoice_number}")
            ->line("- Paket       : {$payment->plan->name}")
            ->when($sub, fn($m) => $m
                ->line("- Aktif sampai: **" . $sub->end_date->format('d F Y') . "**"))
            ->line('Langganan Anda sudah aktif. Selamat menggunakan layanan LOA SIPTENAN!')
            ->action('Buka Dashboard', url('/publisher/dashboard'))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
