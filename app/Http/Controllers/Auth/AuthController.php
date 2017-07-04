<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;


class AuthController extends Controller {


    /**
     * @var \Lcobucci\JWT\Builder;
     */
    protected $jwt;

    public function __construct(Builder $jwt)
    {
        $this->jwt = $jwt;
    }


    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \Illuminate\Http\Response $response
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request, Response $response)
    {
        $response->header("Content-Type", "application/json");
        try
        {
            $this->validate($request, [
                'email' => 'required|email|max:255', 'password' => 'required',
            ]);
        }
        catch (ValidationException $e)
        {
            $response->setStatusCode(IlluminateResponse::HTTP_BAD_REQUEST);
            return $response->setContent([
                'error' => 'Invalid auth'
            ]);
        }

        $credentials = $this->getCredentials($request);

        $userModel = new User();
        $users = $userModel->where('email', 'like', $credentials['email'])
            ->where('password', hash_pw($credentials['password']))
            ->get();
        $user = $users->first();

        // no user then error out
        if ($user == null) {
            $response->setStatusCode(IlluminateResponse::HTTP_BAD_REQUEST);
            return $response->setContent([
                "error" => "invalid credentials"
            ]);
        }

        try
        {
            $token = $this->createToken($user);
        }
        catch (\Exception $e)
        {
            // something went wrong whilst attempting to encode the token
            $response->setStatusCode(500);
            return $response->setContent(['error' => 'could_not_create_token']);
        }

        // all good so return the token
        return $response->setContent(['token'=>"$token"]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    /**
     * @param User $user
     * @return \Lcobucci\JWT\Token
     */
    protected function createToken(User $user)
    {
        return $this->jwt->setIssuer(getenv('JWT_ISSUER_URL')) // Configures the issuer (iss claim)
            ->setAudience(getenv('JWT_AUDIENCE_URL')) // Configures the audience (aud claim)
            ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
            ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
            ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
            ->set('uid', $user->id) // Configures a new claim, called "uid"
            ->set('name', $user->name) // Configures a new claim, called "name"
            ->set('email', $user->email) // Configures a new claim, called "uid"
            ->sign(new Sha256(), getenv('JWT_SECRET'))
            ->getToken();
    }
}
