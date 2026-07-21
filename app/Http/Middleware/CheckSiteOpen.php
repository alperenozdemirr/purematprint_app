<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiteOpen
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin', 'admin/*')) {
            return $next($request);
        }

        $setting = Setting::current();

        if ($setting->site_open) {
            return $next($request);
        }

        if ($request->routeIs('maintenance')) {
            return $next($request);
        }

        return redirect()->route('maintenance');
    }
}
