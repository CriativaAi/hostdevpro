<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'plesk' => [
        'host' => env('PLESK_HOST', 'https://us163-pl.valueserver.net:8443'),
        'username' => env('PLESK_USERNAME', 'alexcla1'),
        'password' => env('PLESK_PASSWORD'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'restricted_key' => env('STRIPE_RESTRICTED_KEY'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
    ],

    'mercadopago' => [
        'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),
        'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),
        'client_id' => env('MERCADOPAGO_CLIENT_ID'),
        'client_secret' => env('MERCADOPAGO_CLIENT_SECRET'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
    ],


    'evolution' => [
        'url' => env('EVOLUTION_API_URL', 'http://127.0.0.1:8080'),
        'instance' => env('EVOLUTION_INSTANCE', 'HostDevPro'),
        'token' => env('EVOLUTION_INSTANCE_TOKEN', 'E530B747A900-469A-BB8E-453FFC6032C2'),
        'global_key' => env('EVOLUTION_GLOBAL_KEY', 'G7aFFCFZHKXDdSW7yBx2Pc5xfCZ5dxaW'),
    ],

    'n8n' => [
        'webhook_url' => env('N8N_WEBHOOK_URL', 'http://127.0.0.1:5678/webhook/hostdevpro-invoices'),
    ],
];
