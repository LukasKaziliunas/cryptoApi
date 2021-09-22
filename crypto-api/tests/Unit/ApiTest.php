<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Interfaces\CryptoApi;
use App\Services\CoinlayerApi;
use App\Services\CoinMarketCapApi;

class ApiTests extends TestCase
{

    public function test_coinmarketcap_api_correct_result()
    {
        $cmcApi = new CoinMarketCapApi();

        $requiredCryptos = CryptoApi::CRYPTOS;

        $rates = $cmcApi->getRates();     

        $this->assertTrue($this->allRequiredCryptosInArray($requiredCryptos, $rates));
    }

    public function test_coinlayer_api_correct_result()
    {
        $cmcApi = new CoinlayerApi();

        $requiredCryptos = CryptoApi::CRYPTOS;

        $rates = $cmcApi->getRates();    

        $this->assertTrue($this->allRequiredCryptosInArray($requiredCryptos, $rates));
    }

    private function allRequiredCryptosInArray($requiredCryptos, $array)
    {
        foreach($requiredCryptos as $rc)
        {
            if(!isset($array[$rc]))  
                 return false;
        }
        return true;
    }

}
