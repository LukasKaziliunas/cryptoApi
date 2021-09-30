<?php

return [
    'default' => env('CRYPTO_API', 'mockcoinmarketcap'),

    'cryptos' => ["BTC", "ETH", "DOGE"],

    'coinmarketcap' => [
        'url' => 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest',
        'key' => env('COINMARKETCAP_KEY', ''),
        'limit' => env('COINMARKETCAP_DAILY_LIMIT', 0),
    ],

    'coinlayer' => [
        'url' =>  'http://api.coinlayer.com/api/live',
        'key' => env('COINLAYER_KEY', ''),
        'limit' => env('COINLAYER_DAILY_LIMIT', 0), 
    ],

];