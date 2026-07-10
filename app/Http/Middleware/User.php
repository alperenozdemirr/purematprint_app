<?php

namespace App\Http\Middleware;

use App\Enums\Status;
use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->type === UserType::USER && $user->status === Status::ACTIVE) {
            return $next($request);
        }

        return redirect(route('loginPage'))->with('error', 'Bu sayfaya erişim yetkiniz yok.');
    }
}
