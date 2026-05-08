<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::whereIn('domain_status', ['pending', 'active', 'rejected'])
            ->with('user')
            ->orderByRaw("FIELD(domain_status, 'pending', 'active', 'rejected')")
            ->orderBy('updated_at', 'desc');

        if ($request->filled('status')) {
            $query->where('domain_status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'like', "%$q%")
                   ->orWhere('subdomain', 'like', "%$q%")
                   ->orWhere('custom_domain', 'like', "%$q%");
            });
        }

        $publishers = $query->paginate(20)->withQueryString();
        $pendingCount = Publisher::where('domain_status', 'pending')->count();

        return view('admin.domain-requests.index', compact('publishers', 'pendingCount'));
    }

    public function approve(Request $request, Publisher $publisher)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        $publisher->update([
            'domain_status'      => 'active',
            'domain_notes'       => $request->notes,
            'domain_approved_at' => now(),
            'domain_approved_by' => Auth::id(),
        ]);

        return back()->with('success', "Domain untuk publisher {$publisher->name} berhasil diaktifkan.");
    }

    public function reject(Request $request, Publisher $publisher)
    {
        $request->validate(['notes' => 'required|string|max:500']);

        $publisher->update([
            'domain_status' => 'rejected',
            'domain_notes'  => $request->notes,
        ]);

        return back()->with('success', "Permintaan domain publisher {$publisher->name} ditolak.");
    }

    public function revoke(Publisher $publisher)
    {
        $publisher->update([
            'subdomain'          => null,
            'custom_domain'      => null,
            'domain_status'      => 'none',
            'domain_notes'       => null,
            'domain_approved_at' => null,
            'domain_approved_by' => null,
        ]);

        return back()->with('success', "Domain publisher {$publisher->name} berhasil dicabut.");
    }
}
