<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\LoaRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalRequests = LoaRequest::count();
        $approvedRequests = LoaRequest::where('status', 'approved')->count();
        $pendingRequests = LoaRequest::where('status', 'pending')->count();
        $totalJournals = Journal::count();

        return view('home', compact(
            'totalRequests',
            'approvedRequests', 
            'pendingRequests',
            'totalJournals'
        ));
    }
}
