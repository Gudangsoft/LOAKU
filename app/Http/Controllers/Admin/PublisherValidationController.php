<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PublisherValidationController extends Controller
{
    /**
     * Show publisher validation management page
     */
    public function index()
    {
        $pendingPublishers = Publisher::where('status', 'pending')
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $activePublishers = Publisher::where('status', 'active')
            ->with(['user', 'validator'])
            ->orderBy('validated_at', 'desc')
            ->get();

        $suspendedPublishers = Publisher::where('status', 'suspended')
            ->with(['user', 'validator'])
            ->orderBy('validated_at', 'desc')
            ->get();

        return view('admin.publisher-validation.index', compact('pendingPublishers', 'activePublishers', 'suspendedPublishers'));
    }

    /**
     * Show publisher validation detail
     */
    public function show(Publisher $publisher)
    {
        $publisher->load(['user', 'validator', 'journals']);
        return view('admin.publisher-validation.show', compact('publisher'));
    }

    /**
     * Activate publisher
     */
    public function activate(Request $request, Publisher $publisher)
    {
        $validator = Validator::make($request->all(), [
            'validation_notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate new token if not exists
        if (!$publisher->validation_token) {
            $publisher->generateValidationToken();
        }

        // Activate publisher
        $publisher->activate(Auth::id(), $request->validation_notes);

        // Send notification to publisher (placeholder)
        $this->notifyPublisherActivation($publisher);

        return redirect()->route('admin.publisher-validation.index')
            ->with('success', "Publisher {$publisher->name} berhasil diaktifkan! Token: {$publisher->validation_token}");
    }

    /**
     * Suspend publisher
     */
    public function suspend(Request $request, Publisher $publisher)
    {
        $validator = Validator::make($request->all(), [
            'validation_notes' => 'required|string|max:500'
        ], [
            'validation_notes.required' => 'Alasan suspend wajib diisi.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Suspend publisher
        $publisher->suspend(Auth::id(), $request->validation_notes);

        // Send notification to publisher (placeholder)
        $this->notifyPublisherSuspension($publisher);

        return redirect()->route('admin.publisher-validation.index')
            ->with('warning', "Publisher {$publisher->name} berhasil disuspend.");
    }

    /**
     * Regenerate validation token
     */
    public function regenerateToken(Publisher $publisher)
    {
        $oldToken = $publisher->validation_token;
        $newToken = $publisher->generateValidationToken();

        return redirect()->back()
            ->with('success', "Token baru berhasil dibuat untuk {$publisher->name}: {$newToken}")
            ->with('info', "Token lama: {$oldToken}");
    }

    /**
     * Bulk activate publishers
     */
    public function bulkActivate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'publisher_ids' => 'required|array',
            'publisher_ids.*' => 'exists:publishers,id',
            'bulk_notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $publishers = Publisher::whereIn('id', $request->publisher_ids)
            ->where('status', 'pending')
            ->get();

        $activated = 0;
        foreach ($publishers as $publisher) {
            if (!$publisher->validation_token) {
                $publisher->generateValidationToken();
            }
            $publisher->activate(Auth::id(), $request->bulk_notes);
            $this->notifyPublisherActivation($publisher);
            $activated++;
        }

        return redirect()->route('admin.publisher-validation.index')
            ->with('success', "{$activated} publisher berhasil diaktifkan secara bulk.");
    }

    /**
     * Get publisher validation statistics
     */
    public function statistics()
    {
        $stats = [
            'pending' => Publisher::where('status', 'pending')->count(),
            'active' => Publisher::where('status', 'active')->count(),
            'suspended' => Publisher::where('status', 'suspended')->count(),
            'total' => Publisher::count(),
            'today_registrations' => Publisher::whereDate('created_at', today())->count(),
            'today_activations' => Publisher::whereDate('validated_at', today())->where('status', 'active')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Send activation notification to publisher
     */
    private function notifyPublisherActivation($publisher)
    {
        try {
            // TODO: Implement email notification
            \Log::info("Publisher activated: {$publisher->name} (Token: {$publisher->validation_token})");
        } catch (\Exception $e) {
            \Log::error('Failed to send activation notification: ' . $e->getMessage());
        }
    }

    /**
     * Send suspension notification to publisher
     */
    private function notifyPublisherSuspension($publisher)
    {
        try {
            // TODO: Implement email notification
            \Log::info("Publisher suspended: {$publisher->name}");
        } catch (\Exception $e) {
            \Log::error('Failed to send suspension notification: ' . $e->getMessage());
        }
    }
}
