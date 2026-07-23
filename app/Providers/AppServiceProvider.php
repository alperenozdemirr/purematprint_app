<?php

namespace App\Providers;

use App\View\Composers\AdminLayoutComposer;
use App\View\Composers\UserLayoutComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('user.*', UserLayoutComposer::class);
        View::composer(['admin.layout', 'admin.default.login'], AdminLayoutComposer::class);
    }
}
