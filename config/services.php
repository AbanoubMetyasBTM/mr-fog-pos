<?php

return [

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'google' => [
        'client_id'     => '941333728413-mtm8h4hqhrk6jp6f245ccv0tp4i07i1m.apps.googleusercontent.com',
        'client_secret' => 'e3KXiDAY57WLWovD6rKODrR_',
        'redirect'      => env("APP_URL") . "/social-callback/google",
    ],

    'facebook' => [
        'client_id'     => '2522651991195617',
        'client_secret' => 'a177c0281c2a35e3b51e89d2615c0c24',
        'redirect'      => env("APP_URL") . "/social-callback/facebook",
    ],


    //    'twitter' => [
    //        'client_id'     => 'ZnfKKkDMvGbsTSzXnKUEDk8AY',
    //        'client_secret' => 'DxGFloSd6xGOggfljKxPCTRPX5ej5OrzjpOX4Q6VetFgHWz0F0',
    //        'redirect'      => url("/tw_callback"),
    //    ],


];
