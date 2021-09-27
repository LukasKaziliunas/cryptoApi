<?php

namespace Tests\Unit;

use App\Services\AssetsService;
use ErrorException;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    public function test_calculates_correct_total_portfolio_value()
    {
        $rates = [
            'BTC' => 40000,
            'ETH' => 1000
        ];

        $portfolio = [
            (object)['crypto' => "BTC", 'amount' => 1.5],
            (object)['crypto' => "ETH", 'amount' => 2],
        ];

        $total = AssetsService::calculateAssetsTotal($rates, $portfolio);

        $this->assertTrue($total === 62000.0);
    }

    public function test_calculates_correct_crypto_price()
    {
        try
        {
            $price = AssetsService::calculateAssetPrice('BTC', 1.5);
        }
        catch(ErrorException $e)
        {
            $this->assertFalse(true);
        }

        $this->assertTrue(is_float($price));
    }
}
