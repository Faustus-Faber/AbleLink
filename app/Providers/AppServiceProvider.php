<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // Gate for employers to manage jobs
        Gate::define('manage-jobs', function ($user) {
            return $user->role === 'employer';
        });

        // Gate for admin access
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
