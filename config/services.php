<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => '',
        'secret' => '',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => 'User',
        'secret' => '',
    ],

    'github' => [
        'client_id' => '4bbf037827ace2e23b1f',
        'client_secret' => 'beace660c4c6d4c0ecefac133c7997364d07228c',
        'redirect' => $_ENV['APP_URL'] . '/admin/integrations/callback/github',
    ],

    'bitbucket' => [
        'client_id' => 'gS2JYF5M69zxgRu8G3',
        'client_secret' => 'BeQaWZPyYPwBQyRMHTSNsUFGrPPxaAZR',
        'redirect' => $_ENV['APP_URL'] . '/admin/integrations/callback/bitbucket',
    ],

];
