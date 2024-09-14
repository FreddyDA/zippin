<?php

namespace App\Providers;

use App\Models\Orders;
use App\Policies\OrderPolicy;
use App\Models\Products;
use App\Policies\ProductPolicy;
use App\Models\Users;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Orders::class => OrderPolicy::class,
        Products::class => ProductPolicy::class,
        Users::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        #$this->registerPolicies();
        Gate::policy(Orders::class, OrderPolicy::class);
        Gate::policy(Products::class, ProductPolicy::class);
        Gate::policy(Users::class, UserPolicy::class);
    }
}
