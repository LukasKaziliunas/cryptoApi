<?php

namespace Tests\Unit;

use App\Interfaces\CryptoApi;
use App\Services\CoinlayerApi;
use App\Services\CoinMarketCapApi;
use Tests\TestCase;

class ApiTests extends TestCase
{

    public function testCoinmarketcapApiGivesCorrectResult()
    {
        $cmcApi = new CoinMarketCapApi();

        $requiredCryptos = CryptoApi::CRYPTOS;

        $rates = $cmcApi->getRates();

        $this->assertTrue($this->allRequiredCryptosInArray($requiredCryptos, $rates));
    }

    public function testCoinlayerApiGivesCorrectResult()
    {
        $cmcApi = new CoinlayerApi();

        $requiredCryptos = CryptoApi::CRYPTOS;

        $rates = $cmcApi->getRates();

        $this->assertTrue($this->allRequiredCryptosInArray($requiredCryptos, $rates));
    }

    private function allRequiredCryptosInArray($requiredCryptos, $array)
    {
        foreach ($requiredCryptos as $rc) {
            if (!isset($array[$rc])) {
                return false;
            }

        }
        return true;
    }

}
