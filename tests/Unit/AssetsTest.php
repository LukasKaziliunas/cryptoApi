<?php

namespace Tests\Unit;

use App\Models\Asset;
use App\Models\User;
use App\Services\AssetsService;
use Tests\TestCase;

class AssetsTest extends TestCase
{

    private $assetService;

    public function setUp(): void
    {
        parent::setUp();
        $this->assetService = resolve(AssetsService::class);
    }

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

        $total = $this->assetService->calculateAssetsTotal($rates, $portfolio);

        $this->assertTrue($total === 62000.0);
    }

    public function testCalculatesCorrectCryptoPrice()
    {

        $price = $this->assetService->calculateAssetPrice('BTC', 1.5);

        $this->assertTrue(is_float($price));
        $this->assertTrue($price == 60000.0);
    }

    public function testCalculatesUserPortfolioTotal()
    {
        $u = User::factory()->create();

        $a1 = Asset::factory()->create(['crypto' => "BTC", 'amount' => 0.5, 'user_id' => $u->id]);
        $a2 = Asset::factory()->create(['crypto' => "ETH", 'amount' => 1, 'user_id' => $u->id]);

        $price = $this->assetService->getPortfolioTotal($u->id);

        $this->assertTrue(is_float($price));
        $this->assertTrue($price == 23000.0);
    }
}
