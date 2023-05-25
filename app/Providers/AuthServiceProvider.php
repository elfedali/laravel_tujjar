<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('super-admin', function ($user) {
            return $user->role === User::ROLE_SUPER_ADMIN;
        });

        Gate::define('admin', function ($user) {
            return $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_SUPER_ADMIN;
        });

        Gate::define('user', function ($user) {
            return $user->role === User::ROLE_USER || $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_SUPER_ADMIN;
        });

        Gate::define('update-user', function ($user, $model) {
            return $user->id === $model->id;
        });

        Gate::define('update', function ($user, $model) {
            return $user->id === $model->user_id;
        });
        Gate::define('delete', function ($user, $model) {
            return $user->id === $model->user_id;
        });

        // Shop
        Gate::define('update-shop', function ($user, $model) {
            return $user->id === $model->owner_id;
        });
    }
}
