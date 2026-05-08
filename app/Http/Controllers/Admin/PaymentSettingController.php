<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    private array $textKeys = [
        'payment_bank_name',
        'payment_bank_account_number',
        'payment_bank_account_name',
        'payment_instructions',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->textKeys as $key) {
            $settings[$key] = WebsiteSetting::getValue($key, '');
        }
        $settings['payment_bank_logo'] = WebsiteSetting::getValue('payment_bank_logo', '');

        return view('admin.payment-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'payment_bank_name'           => 'nullable|string|max:100',
            'payment_bank_account_number' => 'nullable|string|max:50',
            'payment_bank_account_name'   => 'nullable|string|max:100',
            'payment_instructions'        => 'nullable|string|max:1000',
            'payment_bank_logo'           => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
        ]);

        foreach ($this->textKeys as $key) {
            WebsiteSetting::set($key, $request->input($key, ''), 'text');
        }

        if ($request->hasFile('payment_bank_logo')) {
            $old = WebsiteSetting::getValue('payment_bank_logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('payment_bank_logo')->store('payment', 'public');
            WebsiteSetting::set('payment_bank_logo', $path, 'image');
        }

        return back()->with('success', 'Pengaturan rekening pembayaran berhasil disimpan.');
    }
}
