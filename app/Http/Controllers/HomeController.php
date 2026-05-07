<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\LoaRequest;
use App\Models\Publisher;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalRequests = LoaRequest::count();
        $approvedRequests = LoaRequest::where('status', 'approved')->count();
        $pendingRequests = LoaRequest::where('status', 'pending')->count();
        $totalJournals = Journal::count();
        $publishers = Publisher::where('status', 'active')->orderBy('name')->limit(6)->get();
        $totalPublishers = Publisher::where('status', 'active')->count();

        return view('home', compact(
            'totalRequests',
            'approvedRequests',
            'pendingRequests',
            'totalJournals',
            'publishers',
            'totalPublishers'
        ));
    }

    public function publishers(Request $request)
    {
        $search = $request->get('q');

        $publishers = Publisher::where('status', 'active')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->withCount('journals')
            ->orderBy('name')
            ->paginate(12);

        $totalPublishers = Publisher::where('status', 'active')->count();

        return view('publishers.index', compact('publishers', 'totalPublishers', 'search'));
    }

    public function publisherDetail($id)
    {
        $publisher = Publisher::where('status', 'active')
            ->with(['journals' => function ($q) {
                $q->orderBy('name')->withCount('loaRequests');
            }, 'journals.loaRequests'])
            ->findOrFail($id);

        return view('publishers.show', compact('publisher'));
    }
}
