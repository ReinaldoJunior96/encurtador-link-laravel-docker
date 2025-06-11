<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('chat-public', function ($user) {
            return trim($user->role) === 'admin' || trim($user->role) === 'funcionario';
        });
        Gate::define('manage-users', function ($user) {
            return trim($user->role) === 'admin';
        });
    }
}
