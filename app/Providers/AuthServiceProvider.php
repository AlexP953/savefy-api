<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Laravel\Passport\Passport;
use League\OAuth2\Server\Grant\PasswordGrant;
use App\Models\User;
use Laravel\Passport\PersonalAccessTokenResult;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        $grant = new PasswordGrant(new \App\Repositories\UserRepository());

        Passport::enableGrantType($grant, now()->addDay());

    }

    public function register(): void {

        Passport::enablePasswordGrant();
        
    }

}
