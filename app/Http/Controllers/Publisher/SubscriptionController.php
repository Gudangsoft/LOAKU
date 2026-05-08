<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $publisher = Publisher::where('user_id', Auth::id())->first();

        if (!$publisher) {
            return redirect()->route('publisher.dashboard')
                ->with('error', 'Data publisher tidak ditemukan.');
        }

        $activeSubscription = $publisher->activeSubscription;
        $history = $publisher->subscriptions()
            ->with('plan')
            ->orderByDesc('start_date')
            ->get();

        $plans = SubscriptionPlan::active()->orderBy('sort_order')->get();

        // Usage stats
        $journalCount = $publisher->journals()->count();
        $loaThisMonth = 0;
        if ($activeSubscription) {
            $journalIds = $publisher->journals()->pluck('id');
            $loaThisMonth = \App\Models\LoaRequest::whereIn('journal_id', $journalIds)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count();
        }

        return view('publisher.subscription.index', compact(
            'publisher',
            'activeSubscription',
            'history',
            'plans',
            'journalCount',
            'loaThisMonth'
        ));
    }
}
