<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for packages to have a conventional file to locate the various
    | service credentials.
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
        'region' => env(
            'AWS_DEFAULT_REGION',
            'us-east-1'
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Gemini
    |--------------------------------------------------------------------------
    */

    'gemini' => [

        'api_key' => env('GEMINI_API_KEY'),

        'model' => env(
            'GEMINI_MODEL',
            'gemini-2.5-flash'
        ),

        'triagem_cache' => env('GEMINI_TRIAGEM_CACHE'),

        /*
        |--------------------------------------------------------------------------
        | Estimativa de custos e consumo
        |--------------------------------------------------------------------------
        |
        | Os preços são informados em dólares por milhão de tokens.
        | A cotação do dólar é usada para estimar o custo em reais.
        | Preencha os valores no .env conforme o modelo e os preços
        | efetivamente aplicáveis à sua conta do Gemini.
        |
        */

        'usd_brl_rate' => env('GEMINI_USD_BRL_RATE'),

        'pricing' => [
            'input_per_million_usd' => env(
                'GEMINI_INPUT_PRICE_PER_MILLION_USD'
            ),

            'output_per_million_usd' => env(
                'GEMINI_OUTPUT_PRICE_PER_MILLION_USD'
            ),

            'cached_input_per_million_usd' => env(
                'GEMINI_CACHED_INPUT_PRICE_PER_MILLION_USD'
            ),
        ],
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' =>
                env('SLACK_BOT_USER_OAUTH_TOKEN'),

            'channel' =>
                env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
