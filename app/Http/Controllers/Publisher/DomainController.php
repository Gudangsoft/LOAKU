<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function index()
    {
        $publisher = Publisher::where('user_id', Auth::id())->firstOrFail();
        $canUseDomain = $publisher->canRequestDomain();

        return view('publisher.domain.index', compact('publisher', 'canUseDomain'));
    }

    public function update(Request $request)
    {
        $publisher = Publisher::where('user_id', Auth::id())->firstOrFail();

        if (!$publisher->canRequestDomain()) {
            return back()->with('error', 'Paket langganan Anda tidak mendukung fitur domain kustom. Upgrade paket terlebih dahulu.');
        }

        $request->validate([
            'type'          => 'required|in:subdomain,custom_domain',
            'subdomain'     => 'nullable|required_if:type,subdomain|alpha_dash|min:3|max:50|unique:publishers,subdomain,' . $publisher->id,
            'custom_domain' => 'nullable|required_if:type,custom_domain|regex:/^([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,}$/|max:255|unique:publishers,custom_domain,' . $publisher->id,
        ], [
            'subdomain.required_if'     => 'Nama subdomain wajib diisi.',
            'subdomain.alpha_dash'      => 'Subdomain hanya boleh huruf, angka, dan tanda hubung.',
            'subdomain.min'             => 'Subdomain minimal 3 karakter.',
            'subdomain.unique'          => 'Subdomain ini sudah digunakan publisher lain.',
            'custom_domain.required_if' => 'Nama domain wajib diisi.',
            'custom_domain.regex'       => 'Format domain tidak valid, contoh: loa.namajournal.ac.id',
            'custom_domain.unique'      => 'Domain ini sudah terdaftar di sistem.',
        ]);

        $data = ['domain_status' => 'pending', 'domain_notes' => null];

        if ($request->type === 'subdomain') {
            $data['subdomain'] = strtolower($request->subdomain);
            $data['custom_domain'] = null;
        } else {
            $data['custom_domain'] = strtolower($request->custom_domain);
            $data['subdomain'] = null;
        }

        $publisher->update($data);

        return back()->with('success', 'Permintaan domain berhasil dikirim. Admin akan memverifikasi dan mengaktifkan domain Anda.');
    }

    public function cancel(Request $request)
    {
        $publisher = Publisher::where('user_id', Auth::id())->firstOrFail();

        $publisher->update([
            'subdomain'     => null,
            'custom_domain' => null,
            'domain_status' => 'none',
            'domain_notes'  => null,
        ]);

        return back()->with('success', 'Permintaan domain dibatalkan.');
    }
}
