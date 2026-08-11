<?php

namespace App\Providers;

use App\Enums\Status;
use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Configure Horizon authorization.
     *
     * Only active admins authenticated via the admin guard may access /horizon.
     * Local environment is intentionally not auto-allowed.
     */
    protected function authorization(): void
    {
        Horizon::auth(function ($request) {
            $user = Auth::guard('admin')->user();

            return $user
                && $user->type === UserType::ADMIN
                && $user->status === Status::ACTIVE;
        });
    }
}
