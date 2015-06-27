<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Socialite;

use CommerceGuys\Guzzle\Oauth2\GrantType\RefreshToken;
use CommerceGuys\Guzzle\Oauth2\GrantType\PasswordCredentials;
use CommerceGuys\Guzzle\Oauth2\Oauth2Subscriber;


class IntegrationController extends Controller
{
    public function index()
    {
        return view('admin.integrations.listing', [
            'title' => 'testing'
        ]);
    }

    public function auth($service)
    {
        if ($service === 'bitbucket') {
            return Socialite::driver($service)
                            ->redirect();
        }
        else if ($service === 'github') {
            return Socialite::driver($service)
                            ->scopes(['user', 'repo'])
                            ->redirect();   
        }
    }

    public function callback($service)
    {
        $user = Socialite::driver($service)->user();

        if ($service === 'github')
        {
            // OAuth Two Providers
            $token = $user->token;

            $oauth2client = new Client(['base_url' => 'https://api.github.com/']);

            $config = [
                'username' => env('GITHUB_CLIENT_ID'),
                'password' => env('GITHUB_CLIENT_SECRET'),
                'client_id' => 'deployer',
                'scope' => 'user',
            ];

            $token = new PasswordCredentials($oauth2client, $config);
            $refreshToken = new RefreshToken($oauth2client, $config);

            $oauth2 = new Oauth2Subscriber($token, $refreshToken);

            $client = new Client([
                'defaults' => [
                    'auth' => 'oauth2',
                    'subscribers' => [$oauth2],
                ],
            ]);

            $res = $client->get('user');

            
            dd($res->json());


        }
        else if ($service === 'bitbucket')
        {
            // OAuth One Providers
            $token = $user->token;
            $tokenSecret = $user->tokenSecret;

            $client = new Client(['base_url' => 'https://api.bitbucket.org/2.0/']);
            $oauth = new Oauth1([
                'consumer_key'    => env('BITBUCKET_CLIENT_ID'),
                'consumer_secret' => env('BITBUCKET_CLIENT_SECRET'),
                'token'           => $token,
                'token_secret'    => $tokenSecret
            ]);
            $client->getEmitter()->attach($oauth);
            // Set the "auth" request option to "oauth" to sign using oauth
            $res = $client->get('repositories/rebelinblue', ['auth' => 'oauth']);

            dd($res->json());
        }
    }

}


