<?php

namespace App\Services;

use App\Managers\Crypto\RateExchangeManager;
use App\Models\Asset;

class AssetsService
{

    protected $rateManager;

    /**
     * Creates new AssetsService class.
     *
     * @param RateExchangeManager $rateManager
     * @return void
     */
    public function __construct(RateExchangeManager $rateManager)
    {
        $this->rateManager = $rateManager;
    }

    /**
     * get total value of users portfolio.
     *
     * @param  int $userId
     * @return double
     */
    public function getPortfolioTotal($userId)
    {
        $rates = $this->rateManager->getRates();
        $cryptoAmounts = Asset::getUserCryptoAmounts($userId);
        return $this->calculateAssetsTotal($rates, $cryptoAmounts);
    }

    /**
     * get total value of users portfolio.
     *
     * @param  int $userId
     * @return double
     */
    public function calculateAssetsTotal($rates, $cryptoAmounts)
    {
        $total = 0;

        foreach ($cryptoAmounts as $cryptoAmount) {
            $crypto = $cryptoAmount->crypto;
            $total += $cryptoAmount->amount * $rates[$crypto];
        }

        return $total;
    }

    /**
     * Returns the price for the crypto amount.
     *
     * @param  string $crypto
     * @param  double $amount
     * @return double
     */
    public function calculateAssetPrice($crypto, $amount)
    {
        $rates = $this->rateManager->getRates();

        return $rates[$crypto] * $amount;
    }

}
