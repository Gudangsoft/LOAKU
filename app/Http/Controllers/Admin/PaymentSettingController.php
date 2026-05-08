<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function index()
    {
        // Load multi-bank accounts (JSON), fallback to legacy single-bank fields
        $accountsJson = WebsiteSetting::getValue('payment_bank_accounts', '');
        if ($accountsJson) {
            $accounts = json_decode($accountsJson, true) ?: [];
        } else {
            // Migrate legacy single-bank fields automatically
            $legacy = [
                'bank_name'      => WebsiteSetting::getValue('payment_bank_name', ''),
                'account_number' => WebsiteSetting::getValue('payment_bank_account_number', ''),
                'account_name'   => WebsiteSetting::getValue('payment_bank_account_name', ''),
                'logo'           => WebsiteSetting::getValue('payment_bank_logo', ''),
            ];
            $accounts = ($legacy['bank_name'] || $legacy['account_number']) ? [$legacy] : [
                ['bank_name' => '', 'account_number' => '', 'account_name' => '', 'logo' => ''],
            ];
        }

        $whatsapp        = WebsiteSetting::getValue('payment_whatsapp', '');
        $waMessage       = WebsiteSetting::getValue('payment_whatsapp_message',
            'Halo Admin, saya ingin mengkonfirmasi pembayaran paket langganan LOA SIPTENAN. No. Invoice: {invoice}');
        $instructions    = WebsiteSetting::getValue('payment_instructions', '');

        return view('admin.payment-settings.index', compact('accounts', 'whatsapp', 'waMessage', 'instructions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'banks'                    => 'required|array|min:1',
            'banks.*.bank_name'        => 'nullable|string|max:100',
            'banks.*.account_number'   => 'nullable|string|max:50',
            'banks.*.account_name'     => 'nullable|string|max:100',
            'payment_whatsapp'         => 'nullable|string|max:20|regex:/^[0-9]+$/',
            'payment_whatsapp_message' => 'nullable|string|max:500',
            'payment_instructions'     => 'nullable|string|max:1000',
            'logo.*'                   => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
        ], [
            'payment_whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka (tanpa + atau spasi).',
        ]);

        // Process bank accounts with logo uploads
        $banks = $request->input('banks', []);
        $existingLogos = json_decode(WebsiteSetting::getValue('payment_bank_accounts', '[]'), true) ?? [];

        foreach ($banks as $i => &$bank) {
            $bank['bank_name']      = trim($bank['bank_name'] ?? '');
            $bank['account_number'] = trim($bank['account_number'] ?? '');
            $bank['account_name']   = trim($bank['account_name'] ?? '');

            // Handle logo upload for this bank slot
            if ($request->hasFile("logo.{$i}")) {
                // Delete old logo if exists
                $oldLogo = $existingLogos[$i]['logo'] ?? '';
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                $bank['logo'] = $request->file("logo.{$i}")->store('payment', 'public');
            } else {
                // Keep existing logo
                $bank['logo'] = $existingLogos[$i]['logo'] ?? '';
            }

            // Remove logo explicitly if delete flag sent
            if ($request->input("delete_logo.{$i}")) {
                $oldLogo = $bank['logo'] ?? '';
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                $bank['logo'] = '';
            }
        }
        unset($bank);

        // Remove empty rows (all fields empty)
        $banks = array_values(array_filter($banks, fn($b) =>
            !empty($b['bank_name']) || !empty($b['account_number'])
        ));

        if (empty($banks)) {
            $banks = [['bank_name' => '', 'account_number' => '', 'account_name' => '', 'logo' => '']];
        }

        WebsiteSetting::set('payment_bank_accounts',    json_encode($banks),                       'json',  'Daftar Rekening Bank');
        WebsiteSetting::set('payment_whatsapp',         $request->input('payment_whatsapp', ''),   'text',  'WhatsApp Konfirmasi');
        WebsiteSetting::set('payment_whatsapp_message', $request->input('payment_whatsapp_message', ''), 'text', 'Pesan WA Template');
        WebsiteSetting::set('payment_instructions',     $request->input('payment_instructions', ''), 'text', 'Instruksi Pembayaran');

        return back()->with('success', 'Pengaturan pembayaran berhasil disimpan.');
    }
}
