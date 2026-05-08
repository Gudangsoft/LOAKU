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
        $payment      = $this->payment;
        $plan         = $payment->plan;
        $instructions = WebsiteSetting::getValue('payment_instructions', '');
        $whatsapp     = WebsiteSetting::getValue('payment_whatsapp', '');

        // Load multi-bank accounts
        $accountsJson = WebsiteSetting::getValue('payment_bank_accounts', '');
        $accounts     = $accountsJson ? (json_decode($accountsJson, true) ?? []) : [];

        // Fallback to legacy single-bank fields
        if (empty($accounts)) {
            $bank = WebsiteSetting::getValue('payment_bank_name', '');
            $num  = WebsiteSetting::getValue('payment_bank_account_number', '');
            $name = WebsiteSetting::getValue('payment_bank_account_name', '');
            if ($bank || $num) {
                $accounts = [['bank_name' => $bank, 'account_number' => $num, 'account_name' => $name]];
            }
        }

        $mail = (new MailMessage)
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
            ->line('**Informasi Pembayaran:**');

        foreach ($accounts as $acc) {
            if (empty($acc['bank_name']) && empty($acc['account_number'])) continue;
            $mail->line("🏦 **{$acc['bank_name']}**")
                 ->line("  No. Rekening : {$acc['account_number']}")
                 ->line("  Atas Nama    : {$acc['account_name']}");
        }

        if ($instructions) {
            $mail->line('---')->line($instructions);
        }

        if ($whatsapp) {
            $waMsg = urlencode("Halo Admin, saya ingin konfirmasi pembayaran. No. Invoice: {$payment->invoice_number}");
            $mail->line("💬 Konfirmasi via WhatsApp: https://wa.me/{$whatsapp}?text={$waMsg}");
        }

        return $mail
            ->line('Setelah melakukan pembayaran, harap upload bukti transfer melalui dashboard publisher Anda.')
            ->action('Upload Bukti Bayar', url('/publisher/subscription'))
            ->salutation("Salam,\nTim LOA SIPTENAN");
    }
}
