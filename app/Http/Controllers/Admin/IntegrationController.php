<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Socialite;


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


        }
        else if ($service === 'bitbucket')
        {
            // OAuth One Providers
            $token = $user->token;
            $tokenSecret = $user->tokenSecret;

            $client = new Client(['base_url' => 'https://api.bitbucket.org/2.0/']);
            $oauth = new Oauth1([
                'consumer_key'    => 'gS2JYF5M69zxgRu8G3',
                'consumer_secret' => 'BeQaWZPyYPwBQyRMHTSNsUFGrPPxaAZR',
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


