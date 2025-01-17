<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Models\Asset;
use App\Policies\TransactionPolicy;
use App\Policies\AssetPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Transaction::class => TransactionPolicy::class,
        Asset::class => AssetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('access-dashboard', function ($user) {
            return $user->hasRole(['administrator', 'author', 'editor']);
        });
        $this->registerPolicies();
    }
}
