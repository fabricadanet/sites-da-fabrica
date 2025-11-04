<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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
    
    'portainer' => [
        'url' => env('PORTAINER_URL'), // Ex: https://portainer.meudominio.com
        'api_key' => env('PORTAINER_API_KEY'),
        'endpoint_id' => env('PORTAINER_ENDPOINT_ID', 1), // ID do seu endpoint/ambiente Docker
    ],

    'deploy' => [
        'host' => env('DEPLOY_HOST_IP'),         // IP do seu servidor Docker (com Traefik)
        'username' => env('DEPLOY_HOST_USER'),  // Usuário (ex: root ou deploy)
        'private_key' => env('DEPLOY_HOST_PRIVATE_KEY_PATH'), // Ex: /home/user/.ssh/id_rsa
        'base_path' => env('DEPLOY_SITES_PATH', '/var/docker/sites'), // Pasta raiz no host
    ],

    'cpanel' => [
        'host'       => env('CPANEL_HOST'), // Ex: 'https://seuservidor.com:2083'
        'username'   => env('CPANEL_USERNAME'), // O usuário da sua conta cPanel principal
        'api_token'  => env('CPANEL_API_TOKEN'), // O token gerado dentro do cPanel
        'main_domain' => env('CPANEL_MAIN_DOMAIN'), // Ex: 'sitesdafabrica.com.br'
    ],

];
