<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Journal;

class PublisherOwnsJournal
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $journalId = $request->route('journal');
        $journal = Journal::find($journalId);
        if (!$journal || $journal->publisher_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Akses ditolak.');
        }
        return $next($request);
    }
}
