<?php

namespace App\Providers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
            $authHeader = $request->header('Authorization');
            if ($authHeader) {
                $authToken = substr($authHeader, 6);
                $parser  = new Parser();
                $token = $parser->parse(trim($authToken));
                if (($token->verify(new Sha256(), getenv('JWT_SECRET')))) {
                    return \App\Models\User::where('email', $token->getClaim('email'))->first();
                }
            }
        });
    }
}
