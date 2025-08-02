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
        $publishers = Publisher::orderBy('name')->limit(6)->get();
        $totalPublishers = Publisher::count();

        return view('home', compact(
            'totalRequests',
            'approvedRequests', 
            'pendingRequests',
            'totalJournals',
            'publishers',
            'totalPublishers'
        ));
    }
}
