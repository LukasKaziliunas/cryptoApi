<?php

return [
    'default' => env('CRYPTO_API', 'coinmarketcap'),

    'coinmarketcap' => [
        'class' => App\Services\CoinMarketCapApi::class,
        'key' => env('COINMARKETCAP_KEY', ''),
        'limit' => env('COINMARKETCAP_DAILY_LIMIT', 0),
    ],

    'coinlayer' => [
        'class' => App\Services\CoinlayerApi::class,
        'key' => env('COINLAYER_KEY', ''),
        'limit' => env('COINLAYER_DAILY_LIMIT', 0),
    ],

];