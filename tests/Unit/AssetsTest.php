<?php

namespace Tests\Unit;

use App\Services\AssetsService;
use ErrorException;
use Tests\TestCase;

class AssetsTest extends TestCase
{

    public function testCalculatesCorrectTotalPortfolioValue()
    {
        $assetService = resolve(AssetsService::class);

        $rates = [
            'BTC' => 40000,
            'ETH' => 1000,
        ];

        $portfolio = [
            (object) ['crypto' => "BTC", 'amount' => 1.5],
            (object) ['crypto' => "ETH", 'amount' => 2],
        ];

        $total = $assetService->calculateAssetsTotal($rates, $portfolio);

        $this->assertTrue($total === 62000.0);
    }

    public function testCalculatesCorrectCryptoPrice()
    {
        //pries darant testa reikia kad butu nustatytas mock driveris
        $assetService = resolve(AssetsService::class);

        $price = $assetService->calculateAssetPrice('BTC', 1.5);

        $this->assertTrue(is_float($price));
        $this->assertTrue($price == 60000.0);
    }
}
