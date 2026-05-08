<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\PublisherSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PublisherSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = PublisherSubscription::with(['publisher', 'plan'])
            ->latest();

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', 'active')->where('end_date', '>=', now()->toDateString());
            } elseif ($request->status === 'expired') {
                $query->where(function ($q) {
                    $q->where('status', 'expired')
                      ->orWhere(function ($q2) {
                          $q2->where('status', 'active')->where('end_date', '<', now()->toDateString());
                      });
                });
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('plan_id')) {
            $query->where('subscription_plan_id', $request->plan_id);
        }

        if ($request->filled('q')) {
            $query->whereHas('publisher', fn($q) => $q->where('name', 'like', "%{$request->q}%"));
        }

        $subscriptions = $query->paginate(15)->withQueryString();
        $plans = SubscriptionPlan::orderBy('name')->get();

        return view('admin.publisher-subscriptions.index', compact('subscriptions', 'plans'));
    }

    public function create(Request $request)
    {
        $publishers = Publisher::where('status', 'active')->orderBy('name')->get();
        $plans = SubscriptionPlan::active()->orderBy('sort_order')->get();
        $selectedPublisher = $request->filled('publisher_id')
            ? Publisher::find($request->publisher_id)
            : null;

        return view('admin.publisher-subscriptions.create', compact('publishers', 'plans', 'selectedPublisher'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'publisher_id'           => 'required|exists:publishers,id',
            'subscription_plan_id'   => 'required|exists:subscription_plans,id',
            'start_date'             => 'required|date',
            'amount_paid'            => 'nullable|numeric|min:0',
            'notes'                  => 'nullable|string|max:500',
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addMonths($plan->duration_months)->subDay();

        PublisherSubscription::create([
            'publisher_id'         => $validated['publisher_id'],
            'subscription_plan_id' => $validated['subscription_plan_id'],
            'start_date'           => $startDate->toDateString(),
            'end_date'             => $endDate->toDateString(),
            'status'               => 'active',
            'amount_paid'          => $validated['amount_paid'] ?? $plan->price,
            'notes'                => $validated['notes'],
            'created_by'           => auth()->id(),
        ]);

        $publisher = Publisher::findOrFail($validated['publisher_id']);

        return redirect()->route('admin.publisher-subscriptions.index')
            ->with('success', "Langganan paket '{$plan->name}' untuk publisher '{$publisher->name}' berhasil ditambahkan.");
    }

    public function show(PublisherSubscription $publisherSubscription)
    {
        $publisherSubscription->load(['publisher', 'plan', 'creator']);
        return view('admin.publisher-subscriptions.show', compact('publisherSubscription'));
    }

    public function edit(PublisherSubscription $publisherSubscription)
    {
        $plans = SubscriptionPlan::active()->orderBy('sort_order')->get();
        return view('admin.publisher-subscriptions.edit', compact('publisherSubscription', 'plans'));
    }

    public function update(Request $request, PublisherSubscription $publisherSubscription)
    {
        $validated = $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after:start_date',
            'status'               => 'required|in:active,expired,cancelled',
            'amount_paid'          => 'nullable|numeric|min:0',
            'notes'                => 'nullable|string|max:500',
        ]);

        $publisherSubscription->update($validated);

        return redirect()->route('admin.publisher-subscriptions.index')
            ->with('success', 'Langganan berhasil diperbarui.');
    }

    public function destroy(PublisherSubscription $publisherSubscription)
    {
        $publisherSubscription->delete();
        return redirect()->route('admin.publisher-subscriptions.index')
            ->with('success', 'Data langganan berhasil dihapus.');
    }

    public function cancel(PublisherSubscription $publisherSubscription)
    {
        $publisherSubscription->update(['status' => 'cancelled']);
        return back()->with('success', 'Langganan berhasil dibatalkan.');
    }
}
