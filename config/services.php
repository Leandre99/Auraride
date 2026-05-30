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

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
    ],

    'brevo' => [
        'key' => env('BREVO_API_KEY'),
        'sms_sender' => env('BREVO_SMS_SENDER', 'ATLAS VTC'),
    ],

    'ovh_sms' => [
        'endpoint' => env('OVH_SMS_ENDPOINT', 'ovh-eu'),
        'application_key' => env('OVH_SMS_APPLICATION_KEY'),
        'application_secret' => env('OVH_SMS_APPLICATION_SECRET'),
        'consumer_key' => env('OVH_SMS_CONSUMER_KEY'),
        'service_name' => env('OVH_SMS_SERVICE_NAME'),
        'sender' => env('OVH_SMS_SENDER', 'ATLAS TAXI'),
    ],

];
