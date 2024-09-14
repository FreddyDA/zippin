<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Models\Orders;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\UsersPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
{
    Gate::policy(Orders::class, OrderPolicy::class);
    Gate::policy(Products::class, ProductPolicy::class);
    Gate::policy(Users::class, UserPolicy::class);
}


}
