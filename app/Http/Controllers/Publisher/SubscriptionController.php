<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Notifications\SubscriptionInvoiceNotification;
use App\Notifications\SubscriptionProofReceivedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    private function getPublisher(): ?Publisher
    {
        return Publisher::where('user_id', Auth::id())->first();
    }

    public function index()
    {
        $publisher = $this->getPublisher();
        if (!$publisher) {
            return redirect()->route('publisher.dashboard')->with('error', 'Data publisher tidak ditemukan.');
        }

        $activeSubscription = $publisher->activeSubscription;
        $latestPayment      = $publisher->payments()->with('plan')->latest()->first();
        $paymentHistory     = $publisher->payments()->with('plan')->latest()->get();
        $plans              = SubscriptionPlan::active()->orderBy('sort_order')->get();

        $journalCount   = $publisher->journals()->count();
        $loaThisMonth   = 0;
        if ($activeSubscription) {
            $ids = $publisher->journals()->pluck('id');
            $loaThisMonth = \App\Models\LoaRequest::whereIn('journal_id', $ids)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();
        }

        return view('publisher.subscription.index', compact(
            'publisher', 'activeSubscription', 'latestPayment',
            'paymentHistory', 'plans', 'journalCount', 'loaThisMonth'
        ));
    }

    public function selectPlan(Request $request)
    {
        $request->validate(['plan_id' => 'required|exists:subscription_plans,id']);

        $publisher = $this->getPublisher();
        if (!$publisher) return back()->with('error', 'Data publisher tidak ditemukan.');

        $plan = SubscriptionPlan::active()->findOrFail($request->plan_id);

        // Cek jika sudah ada invoice pending
        $existing = $publisher->payments()
            ->whereIn('status', ['pending_payment', 'proof_uploaded'])
            ->latest()->first();
        if ($existing) {
            return back()->with('error', 'Anda masih memiliki tagihan yang belum diselesaikan ('. $existing->invoice_number .'). Selesaikan pembayaran sebelumnya terlebih dahulu.');
        }

        // Buat invoice
        $payment = SubscriptionPayment::create([
            'publisher_id'         => $publisher->id,
            'subscription_plan_id' => $plan->id,
            'invoice_number'       => SubscriptionPayment::generateInvoiceNumber(),
            'amount'               => $plan->price,
            'status'               => 'pending_payment',
        ]);

        // Kirim email invoice
        try {
            Auth::user()->notify(new SubscriptionInvoiceNotification($payment));
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim invoice email: ' . $e->getMessage());
        }

        return redirect()->route('publisher.subscription.index')
            ->with('success', "Invoice #{$payment->invoice_number} berhasil dibuat. Cek email Anda untuk detail pembayaran.");
    }

    public function uploadProof(Request $request, SubscriptionPayment $payment)
    {
        $publisher = $this->getPublisher();
        if (!$publisher || $payment->publisher_id !== $publisher->id) abort(403);

        if (!in_array($payment->status, ['pending_payment', 'rejected'])) {
            return back()->with('error', 'Bukti bayar tidak dapat diupload untuk status ini.');
        }

        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ], [
            'payment_proof.required' => 'File bukti pembayaran wajib diupload.',
            'payment_proof.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'payment_proof.max'      => 'Ukuran file maksimal 3 MB.',
        ]);

        // Hapus bukti lama jika ada
        if ($payment->payment_proof) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $payment->update([
            'payment_proof' => $path,
            'status'        => 'proof_uploaded',
            'admin_notes'   => null,
        ]);

        // Notifikasi admin
        try {
            $admins = User::where('is_admin', true)->orWhere('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new SubscriptionProofReceivedNotification($payment));
            }
        } catch (\Exception $e) {
            \Log::warning('Gagal notif admin: ' . $e->getMessage());
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Admin akan segera memverifikasi.');
    }
}
