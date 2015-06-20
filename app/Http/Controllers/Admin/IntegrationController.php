<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use League\OAuth1\Client\Server\Bitbucket;
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

        $server = $this->bitbucket();
        
        // Retrieve temporary credentials
        $temporaryCredentials = $server->getTemporaryCredentials();

        Session::flash('temporary_credentials', serialize($temporaryCredentials));

        // Second part of OAuth 1.0 authentication is to redirect the
        // resource owner to the login screen on the server.
        $url = $server->getAuthorizationUrl($temporaryCredentials);

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

dd($res->json());


        }
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
}