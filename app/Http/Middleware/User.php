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
        if ($request->user() && $request->user()->type == UserType::USER->value  && $request->user()->status == Status::ACTIVE->value) {
            return $next($request);
        }
        return redirect(route('loginPage'))->with('error' , 'Unauthorized action.');
    }
}
