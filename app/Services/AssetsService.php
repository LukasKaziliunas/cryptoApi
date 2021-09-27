<?php

namespace App\Services;

use App\Interfaces\CryptoApi;
use App\Models\Asset;

class AssetsService
{

    public static function getPortfolioTotal($userId)
    {
        $rates = app()->make(CryptoApi::class)->getRates();
        $cryptoAmounts = Asset::getUserCryptoAmounts($userId);
        return self::calculateAssetsTotal($rates, $cryptoAmounts);
    }

    public static function calculateAssetsTotal($rates, $cryptoAmounts)
    {
        $total = 0;

        foreach($cryptoAmounts as $cryptoAmount)
        {
            $crypto = $cryptoAmount->crypto;
            $total += $cryptoAmount->amount * $rates[ $crypto ];
        }

        return $total;
    }

    public static function calculateAssetPrice($crypto, $amount)
    {
        $cryptoApi = app()->make(CryptoApi::class);
        $rates = $cryptoApi->getRates();

        return $rates[$crypto] * $amount;
    }
}
