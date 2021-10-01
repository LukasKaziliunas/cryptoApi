<?php

namespace App\Managers\Crypto;

use Config;
use Illuminate\Support\Manager;

class RateExchangeManager extends Manager
{
    /**
     * creates a CoinManketCap driver
     *
     * @return Driver CoinMarketCapDriver
     */
    public function createCoinmarketcapDriver()
    {
        $config = Config::get('crypto.coinmarketcap');
        $cryptos = Config::get('crypto.cryptos');

        return new Drivers\CoinMarketCapDriver($config, $cryptos);
    }

    /**
     * creates a CoinLayer driver
     *
     * @return Driver CoinLayer driver
     */
    public function createCoinLayerDriver()
    {
        $config = Config::get('crypto.coinlayer');
        $cryptos = Config::get('crypto.cryptos');

        return new Drivers\CoinLayerDriver($config, $cryptos);
    }

    /**
     * creates a Mock driver
     *
     * @return Driver mock driver
     */
    public function createMockCryptoApiDriver()
    {
        return new Drivers\MockCryptoApiDriver();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return Config::get('crypto.default');
    }
}
