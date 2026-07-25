<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Order\Concerns;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait RestoresPaymentSession
{
    protected function restoreAuthenticatedUser(Request $request, ?User $user): void
    {
        if ($user === null) {
            return;
        }

        Auth::login($user);
        $request->session()->regenerate();
    }

    protected function redirectToCheckout(string $message): RedirectResponse
    {
        return redirect()
            ->route('checkout')
            ->with('error', $message);
    }

    protected function redirectToOrder(Order $order, string $type, string $message): RedirectResponse
    {
        $redirect = redirect()->route('orderShow', $order->code);

        return $type === 'success'
            ? $redirect->with('success', $message)
            : $redirect->with('error', $message);
    }
}
