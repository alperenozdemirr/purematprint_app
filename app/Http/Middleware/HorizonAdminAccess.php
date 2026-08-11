<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\Status;
use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HorizonAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('admin')->user();

        if ($user && $user->type === UserType::ADMIN && $user->status === Status::ACTIVE) {
            return $next($request);
        }

        if (! Auth::guard('admin')->check()) {
            return redirect()->guest(route('admin.loginPage'));
        }

        abort(403, 'Bu sayfaya erişim yetkiniz yok.');
    }
}
