<?php

namespace App\Services;

use App\Interfaces\CryptoApi;
use App\Models\Asset;

class AssetsService
{

    /**
     * get total value of users portfolio.
     *
     * @param  int $userId
     * @return double
     */
    public static function getPortfolioTotal($userId)
    {
        $rates = app()->make(CryptoApi::class)->getRates();
        $cryptoAmounts = Asset::getUserCryptoAmounts($userId);
        return self::calculateAssetsTotal($rates, $cryptoAmounts);
    }

    /**
     * get total value of users portfolio.
     *
     * @param  int $userId
     * @return double
     */
    public static function calculateAssetsTotal($rates, $cryptoAmounts)
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
    public static function calculateAssetPrice($crypto, $amount)
    {
        $cryptoApi = app()->make(CryptoApi::class);
        $rates = $cryptoApi->getRates();

        return $rates[$crypto] * $amount;
    }

}
