<?php

// Use the PHP opening tag to indicate the beginning of a PHP script.
// This configuration file defines settings for the Twilio service.

return [
    // 'twilio' configuration settings.
    'twilio' => [
        // Specify the default configuration to use.
        'default' => 'twilio',

        // Manage different Twilio configurations if necessary.
        'connections' => [
            // Details for the 'twilio' connection.
            'twilio' => [
                // Fetch Twilio Account SID from environment variables or use an empty string as default.
                'sid' => env('TWILIO_SID', ''),
                // Fetch Twilio Auth Token from environment variables.
                'token' => env('TWILIO_TOKEN', ''),
                // Fetch the Twilio phone number to send SMS from.
                'from' => env('TWILIO_FROM', ''),
                // Whether to verify Twilio's SSL certificates. It's recommended to keep this true in production.
                'ssl_verify' => env('TWILIO_SSL_VERIFY', true),
            ],
        ],
    ],
];

// No closing PHP tag to comply with the PSR-2 standard and avoid potential issues with output.
