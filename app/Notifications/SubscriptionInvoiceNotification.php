<?php

namespace App\Notifications;

use App\Models\SubscriptionPayment;
use App\Models\WebsiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class SubscriptionInvoiceNotification extends Notification implements ShouldQueue
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
        $plan    = $payment->plan;
        $bank    = WebsiteSetting::getValue('payment_bank_name', '-');
        $accNum  = WebsiteSetting::getValue('payment_bank_account_number', '-');
        $accName = WebsiteSetting::getValue('payment_bank_account_name', '-');
        $instructions = WebsiteSetting::getValue('payment_instructions', '');

        return (new MailMessage)
            ->subject("Invoice Langganan #{$payment->invoice_number} – LOA SIPTENAN")
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line("Terima kasih telah memilih paket **{$plan->name}**.")
            ->line('---')
            ->line('**Detail Invoice:**')
            ->line("- No. Invoice : **{$payment->invoice_number}**")
            ->line("- Paket       : {$plan->name}")
            ->line("- Durasi      : {$plan->duration_months} bulan")
            ->line("- Total Bayar : **Rp " . number_format($payment->amount, 0, ',', '.') . "**")
            ->line('---')
            ->line('**Informasi Pembayaran:**')
            ->line("- Bank  : {$bank}")
            ->line("- No. Rekening : {$accNum}")
            ->line("- Atas Nama    : {$accName}")
            ->when($instructions, fn($m) => $m->line($instructions))
            ->line('Setelah melakukan pembayaran, harap upload bukti transfer melalui dashboard publisher Anda.')
            ->action('Upload Bukti Bayar', url('/publisher/subscription'))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
