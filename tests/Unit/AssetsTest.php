<?php

namespace Tests\Unit;

use App\Services\AssetsService;
use ErrorException;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    public function testCalculatesCorrectTotalPortfolioValue()
    {
        $rates = [
            'BTC' => 40000,
            'ETH' => 1000,
        ];

        $portfolio = [
            (object) ['crypto' => "BTC", 'amount' => 1.5],
            (object) ['crypto' => "ETH", 'amount' => 2],
        ];

        $total = AssetsService::calculateAssetsTotal($rates, $portfolio);

        $this->assertTrue($total === 62000.0);
    }

    public function testCalculatesCorrectCryptoPrice()
    {
        try
        {
            $price = AssetsService::calculateAssetPrice('BTC', 1.5);
        } catch (ErrorException $e) {
            $this->assertFalse(true);
        }

        $this->assertTrue(is_float($price));
    }
}
