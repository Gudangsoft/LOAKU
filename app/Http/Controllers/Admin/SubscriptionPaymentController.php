<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublisherSubscription;
use App\Models\SubscriptionPayment;
use App\Models\User;
use App\Notifications\SubscriptionPaymentConfirmedNotification;
use App\Notifications\SubscriptionPaymentRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubscriptionPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = SubscriptionPayment::with(['publisher', 'plan'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->whereHas('publisher', fn($q) => $q->where('name', 'like', "%{$request->q}%"))
                  ->orWhere('invoice_number', 'like', "%{$request->q}%");
        }

        $payments = $query->paginate(15)->withQueryString();

        $stats = [
            'pending'   => SubscriptionPayment::where('status', 'pending_payment')->count(),
            'uploaded'  => SubscriptionPayment::where('status', 'proof_uploaded')->count(),
            'confirmed' => SubscriptionPayment::where('status', 'confirmed')->count(),
            'rejected'  => SubscriptionPayment::where('status', 'rejected')->count(),
        ];

        return view('admin.subscription-payments.index', compact('payments', 'stats'));
    }

    public function show(SubscriptionPayment $subscriptionPayment)
    {
        $subscriptionPayment->load(['publisher', 'plan', 'confirmedBy']);
        return view('admin.subscription-payments.show', compact('subscriptionPayment'));
    }

    public function confirm(Request $request, SubscriptionPayment $subscriptionPayment)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        if (!in_array($subscriptionPayment->status, ['proof_uploaded', 'pending_payment'])) {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        $subscriptionPayment->update([
            'status'       => 'confirmed',
            'admin_notes'  => $request->notes,
            'confirmed_by' => auth()->id(),
            'confirmed_at' => now(),
        ]);

        // Buat atau perpanjang subscription
        $plan      = $subscriptionPayment->plan;
        $publisher = $subscriptionPayment->publisher;
        $startDate = now()->toDateString();
        $endDate   = now()->addMonths($plan->duration_months)->subDay()->toDateString();

        PublisherSubscription::create([
            'publisher_id'         => $publisher->id,
            'subscription_plan_id' => $plan->id,
            'start_date'           => $startDate,
            'end_date'             => $endDate,
            'status'               => 'active',
            'amount_paid'          => $subscriptionPayment->amount,
            'notes'                => "Konfirmasi invoice {$subscriptionPayment->invoice_number}",
            'created_by'           => auth()->id(),
        ]);

        // Kirim email ke publisher
        try {
            $user = $publisher->user;
            if ($user) {
                $user->notify(new SubscriptionPaymentConfirmedNotification($subscriptionPayment));
            }
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim email konfirmasi: ' . $e->getMessage());
        }

        return redirect()->route('admin.subscription-payments.index')
            ->with('success', "Pembayaran {$subscriptionPayment->invoice_number} dikonfirmasi. Langganan aktif.");
    }

    public function reject(Request $request, SubscriptionPayment $subscriptionPayment)
    {
        $request->validate(['notes' => 'required|string|max:500']);

        if (!in_array($subscriptionPayment->status, ['proof_uploaded', 'pending_payment'])) {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        $subscriptionPayment->update([
            'status'       => 'rejected',
            'admin_notes'  => $request->notes,
            'confirmed_by' => auth()->id(),
            'confirmed_at' => now(),
        ]);

        try {
            $user = $subscriptionPayment->publisher->user;
            if ($user) {
                $user->notify(new SubscriptionPaymentRejectedNotification($subscriptionPayment));
            }
        } catch (\Exception $e) {
            \Log::warning('Gagal kirim email penolakan: ' . $e->getMessage());
        }

        return redirect()->route('admin.subscription-payments.index')
            ->with('success', "Pembayaran {$subscriptionPayment->invoice_number} ditolak.");
    }

    public function destroy(SubscriptionPayment $subscriptionPayment)
    {
        if ($subscriptionPayment->payment_proof) {
            Storage::disk('public')->delete($subscriptionPayment->payment_proof);
        }
        $subscriptionPayment->delete();
        return back()->with('success', 'Data pembayaran dihapus.');
    }
}
