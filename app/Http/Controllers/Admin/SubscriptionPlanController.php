<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->orderBy('price')->get();
        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.subscription-plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:100',
            'description'        => 'nullable|string|max:500',
            'price'              => 'required|numeric|min:0',
            'max_journals'       => 'nullable|integer|min:1',
            'max_loa_per_month'  => 'nullable|integer|min:1',
            'duration_months'    => 'required|integer|min:1',
            'sort_order'         => 'nullable|integer|min:0',
            'is_active'          => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', "Paket langganan '{$validated['name']}' berhasil dibuat.");
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription-plans.edit', compact('subscriptionPlan'));
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:100',
            'description'        => 'nullable|string|max:500',
            'price'              => 'required|numeric|min:0',
            'max_journals'       => 'nullable|integer|min:1',
            'max_loa_per_month'  => 'nullable|integer|min:1',
            'duration_months'    => 'required|integer|min:1',
            'sort_order'         => 'nullable|integer|min:0',
            'is_active'          => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $subscriptionPlan->update($validated);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', "Paket langganan '{$subscriptionPlan->name}' berhasil diperbarui.");
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        if ($subscriptionPlan->subscriptions()->exists()) {
            return back()->with('error', 'Paket tidak dapat dihapus karena masih digunakan oleh publisher.');
        }

        $name = $subscriptionPlan->name;
        $subscriptionPlan->delete();

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', "Paket langganan '{$name}' berhasil dihapus.");
    }
}
