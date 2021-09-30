<?php

namespace App\Managers\Crypto;

use Config;
use Illuminate\Support\Manager;

class RateExchangeManager extends Manager
{
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
        return Config::get('crypto.default');
    }
}