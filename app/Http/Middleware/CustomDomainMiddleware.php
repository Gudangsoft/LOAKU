<?php

namespace App\Http\Middleware;

use App\Models\Publisher;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomDomainMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $mainHost = parse_url(config('app.url'), PHP_URL_HOST) ?? 'loa.siptenan.org';

        // Skip if accessing the main app domain or any of its subpaths
        if ($host === $mainHost || str_ends_with($host, '.' . $mainHost) === false) {
            // Check if it's a fully custom domain (not a subdomain of main)
            if ($host !== $mainHost) {
                $publisher = Publisher::where('custom_domain', $host)
                    ->where('domain_status', 'active')
                    ->first();

                if ($publisher) {
                    $request->attributes->set('custom_domain_publisher', $publisher);
                }
            }
        } else {
            // It's a subdomain of the main domain — extract subdomain
            $sub = str_replace('.' . $mainHost, '', $host);
            if ($sub && $sub !== $mainHost) {
                $publisher = Publisher::where('subdomain', $sub)
                    ->where('domain_status', 'active')
                    ->first();

                if ($publisher) {
                    $request->attributes->set('custom_domain_publisher', $publisher);
                }
            }
        }

        return $next($request);
    }
}
