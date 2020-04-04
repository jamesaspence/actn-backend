<?php

namespace App\Providers;

use App\Services\Auth\AuthTokenGuard;
use Illuminate\Container\Container;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('authToken', function (Container $app, $name, array $config) {
            return new AuthTokenGuard(
                Auth::createUserProvider($config['provider']),
                $app->make(Request::class)
            );
        });
    }
}
