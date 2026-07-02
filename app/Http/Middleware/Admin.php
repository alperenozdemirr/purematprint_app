<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserType;
use App\Enums\Status;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->type == UserType::ADMIN->value  && $request->user()->status == Status::ACTIVE->value) {
            return $next($request);
        }
        return redirect(route('admin.loginPage'))->with('error' , 'Unauthorized action.');
    }
}
