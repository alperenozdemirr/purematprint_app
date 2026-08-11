<?php

namespace App\Providers;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Middleware\HorizonAdminAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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

        // Ensure middleware stays on the group even if config/route cache is stale.
        $this->app['router']->pushMiddlewareToGroup('horizon', HorizonAdminAccess::class);

        // Re-bind auth after all providers boot (prevents local auto-allow fallback).
        $this->app->booted(function () {
            $this->configureHorizonAuth();
        });
    }

    /**
     * Configure Horizon authorization.
     *
     * Only active admins authenticated via the admin guard may access /horizon.
     * Local environment is intentionally not auto-allowed.
     */
    protected function authorization(): void
    {
        $this->configureHorizonAuth();
    }

    protected function gate(): void
    {
        Gate::define('viewHorizon', function ($user = null) {
            return $this->isActiveAdmin($user);
        });
    }

    private function configureHorizonAuth(): void
    {
        Horizon::auth(function ($request) {
            return $this->isActiveAdmin(Auth::guard('admin')->user());
        });
    }

    private function isActiveAdmin(mixed $user): bool
    {
        return $user
            && $user->type === UserType::ADMIN
            && $user->status === Status::ACTIVE;
    }
}
