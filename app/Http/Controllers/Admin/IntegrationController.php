<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use League\OAuth1\Client\Server\Bitbucket;
use League\OAuth2\Client\Provider\Github;
use Session;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

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
        if ($service === 'bitbucket')
        {
            $server = $this->bitbucket();
            
            // Retrieve temporary credentials
            $temporaryCredentials = $server->getTemporaryCredentials();

            Session::flash('temporary_credentials', serialize($temporaryCredentials));

            // Second part of OAuth 1.0 authentication is to redirect the
            // resource owner to the login screen on the server.
            $url = $server->getAuthorizationUrl($com);
        }
        else if ($service === 'github')
        {
            $provider = $this->github();

            // If we don't have an authorization code then get one
            $url = $provider->getAuthorizationUrl();


            Session::flash('oauth2state', $provider->state);

        }



        return redirect($url);
    }

    public function callback($service)
    {
        if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {

            $server = $this->bitbucket();

            // Retrieve the temporary credentials we saved before
            $temporaryCredentials = Session::get('temporary_credentials');

            $temporaryCredentials = unserialize($temporaryCredentials);

            // We will now retrieve token credentials from the server
            $tokenCredentials = $server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);
        }

        if ($service === 'bitbucket') {
            $client = new Client(['base_url' => 'https://api.bitbucket.org/2.0/']);

            $oauth = new Oauth1([
                'consumer_key'    => 'gS2JYF5M69zxgRu8G3',
                'consumer_secret' => 'BeQaWZPyYPwBQyRMHTSNsUFGrPPxaAZR',
                'token'           => $tokenCredentials->getIdentifier(),
                'token_secret'    => $tokenCredentials->getSecret()
            ]);

            $client->getEmitter()->attach($oauth);

            // Set the "auth" request option to "oauth" to sign using oauth
            $res = $client->get('repositories/rebelinblue', ['auth' => 'oauth']);
        }
        else if ($service === 'github')
        {
            if (empty($_GET['state']) || ($_GET['state'] !== Session::get('oauth2state'))) {

                exit('Invalid state');

            }

            $provider = $this->github();

            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {

                // We got an access token, let's now get the user's details
                $userDetails = $provider->getUserDetails($token);

                // Use these details to create a new profile
                printf('Hello %s!', $userDetails->firstName);

            } catch (Exception $e) {

                // Failed to get user details
                exit('Oh dear...');
            }

            dd($token);
            exit;
        }

        dd($res->json());
    }

    private function bitbucket()
    {
        $server = new Bitbucket([
            'identifier' => 'gS2JYF5M69zxgRu8G3',
            'secret' => 'BeQaWZPyYPwBQyRMHTSNsUFGrPPxaAZR',
            'callback_uri' => url('admin/integrations/callback', ['service' => 'bitbucket']),
        ]);


        
        return $server;
    }

    private function github()
    {
        $server = new Github([
            'clientId'      => '4bbf037827ace2e23b1f',
            'clientSecret'  => 'beace660c4c6d4c0ecefac133c7997364d07228c',
            'redirectUri'   => url('admin/integrations/callback', ['service' => 'github']),
            'scopes'        => ['user', 'repo'],
        ]);

        return $server;
    }
}