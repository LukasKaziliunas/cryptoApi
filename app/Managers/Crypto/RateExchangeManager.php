<?php

namespace App\Managers\Crypto;

use Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Manager;

class RateExchangeManager extends Manager
{
    public $now;

    public function __construct()
    {
        $this->now = now()->format('h:i:s');
    }

    public function createCoinmarketcapDriver()
    {
        $config = Config::get('crypto.coinmarketcap');
        $cryptos = Config::get('crypto.cryptos');

        return new Drivers\CoinMarketCapDriver($config, $cryptos);
    }

    public function createCoinLayerDriver()
    {
        $config = Config::get('crypto.coinlayer');
        $cryptos = Config::get('crypto.cryptos');

        return new Drivers\CoinLayerDriver($config, $cryptos);
    }

    public function createMockCryptoApiDriver()
    {
        return new Drivers\MockCryptoApiDriver();
    }

    public function getDefaultDriver()
    {
        error_log($this->now);
        return Config::get('crypto.default');
    }
}
