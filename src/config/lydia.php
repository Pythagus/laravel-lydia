<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lydia models.
    |--------------------------------------------------------------------------
    |
    | This array contains link to themain package models that
    | you can override to custom them. You need to carefully 
    | extends these classes to be working.
    */
    'models' => [
        'payment'     => \Pythagus\LaravelLydia\Models\PaymentLydia::class,
        'transaction' => \Pythagus\LaravelLydia\Models\Transaction::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Lydia main properties
    |--------------------------------------------------------------------------
    |
    | This array lists the properties that won't change
    | from a request to another. This properties are added
    | in the LydiaRequest.executeRequest() method.
    */
    'properties' => [
        // Currency: only EUR and GBP are supported.
        'currency' => 'EUR',
    ],

    /*
    |--------------------------------------------------------------------------
    | Lydia enable status.
    |--------------------------------------------------------------------------
    |
    | This value determines whether the website is able to make
    | Lydia requests.
    */
    'enabled' => (bool) env('LYDIA_ENABLE', false),

    /*
    |--------------------------------------------------------------------------
    | Lydia debugging mode
    |--------------------------------------------------------------------------
    |
    | This value determines whether the website is in a debugging mode.
    | The transactions won't be move money.
    */
    'debug' => (bool) env('LYDIA_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Lydia credentials
    |--------------------------------------------------------------------------
    |
    | This array contains the different PaymentLydia's credentials according
    | to the previous debug value.
    */
    'credentials' => [
        /*
         * Debugging mode.
         */
        'debug' => [
            'url_server'    => 'https://homologation.lydia-app.com',
            'vendor_token'  => env('LYDIA_DEBUG_VENDOR_TOKEN', null),
            'private_token' => env('LYDIA_DEBUG_PRIVATE_TOKEN', null),
        ],

        /*
         * Production mode.
         */
        'prod' => [
            'url_server'    => 'https://lydia-app.com',
            'vendor_token'  => env('LYDIA_PRODUCTION_VENDOR_TOKEN', null),
            'private_token' => env('LYDIA_PRODUCTION_PRIVATE_TOKEN', null),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Lydia's URL
    |--------------------------------------------------------------------------
    |
    | This array contains the available and used Lydia's url
    */
    'url' => [
        /*
         * URL used to make transactions.
         */
        'do' => '/api/request/do.json',

        /*
         * URL used to check the payment state.
         */
        'state' => '/api/request/state.json',

        /*
         * URL used to refund transaction. This
         * route is sometimes comment to be unusable.
         */
        'refund' => '/api/transaction/refund.json',
    ],
];
