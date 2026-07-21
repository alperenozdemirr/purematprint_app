<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Category;
use App\Models\Setting;
use App\Models\ShoppingCart;
use Illuminate\View\View;

class UserLayoutComposer
{
    public function compose(View $view): void
    {
        $authUser = auth()->user();

        $isUserLoggedIn = $authUser
            && $authUser->type === UserType::USER
            && $authUser->status === Status::ACTIVE;

        $userInitials = '';
        $cartCount = 0;

        if ($isUserLoggedIn) {
            $userInitials = collect(preg_split('/\s+/', trim($authUser->name)))
                ->filter()
                ->take(2)
                ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                ->implode('');

            $cartCount = (int) ShoppingCart::query()
                ->where('user_id', $authUser->id)
                ->sum('quantity');
        }

        $view->with([
            'authUser' => $authUser,
            'isUserLoggedIn' => $isUserLoggedIn,
            'userInitials' => $userInitials,
            'cartCount' => $cartCount,
            'menuCategories' => Category::menuTree(),
            'shippingPromoText' => Setting::current()->shippingPromoText(),
        ]);
    }
}
