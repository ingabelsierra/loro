<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {

    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot() {
        $this->registerPolicies();

        Passport::routes();
        
        Passport::tokensExpireIn(now()->addDays(1));

        Passport::refreshTokensExpireIn(now()->addDays(1));

        Passport::personalAccessTokensExpireIn(now()->addDays(1));
    }

}
