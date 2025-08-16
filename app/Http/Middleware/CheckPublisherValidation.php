<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Publisher;

class CheckPublisherValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Allow admin users
        if ($user && ($user->is_admin || $user->hasRole('super_admin') || $user->role === 'admin')) {
            return $next($request);
        }

        // Check if user is publisher
        if ($user && $user->role === 'publisher') {
            $publisher = Publisher::where('user_id', $user->id)->first();
            
            if (!$publisher) {
                // Publisher profile not found, redirect to registration
                return redirect()->route('publisher.register')
                    ->with('error', 'Silakan lengkapi profil publisher terlebih dahulu.');
            }

            // Check publisher validation status
            if ($publisher->status === 'pending') {
                // Redirect to validation pending page
                return redirect()->route('publisher.validation.pending');
            }

            if ($publisher->status === 'suspended') {
                // Redirect to suspended page
                return redirect()->route('publisher.validation.suspended');
            }

            if ($publisher->status === 'active') {
                // Check if publisher has entered token
                if (!session('publisher_validated')) {
                    return redirect()->route('publisher.validation.token');
                }
            }
        }

        return $next($request);
    }
}
