<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\WebsiteSetting;

class ShareWebsiteSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Share website settings to all views
        try {
            $settings = WebsiteSetting::pluck('value', 'key')->toArray();
            View::share('websiteSettings', $settings);
        } catch (\Exception $e) {
            // If settings table doesn't exist yet, share empty array
            View::share('websiteSettings', []);
        }

        return $next($request);
    }
}
