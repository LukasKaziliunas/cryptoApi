<?php

namespace App\Services;

use App\Interfaces\CryptoApi;
use Illuminate\Support\Facades\DB;

class AssetsService
{

    public static function calculateTotal($userId)
    {
        $rates = app()->make(CryptoApi::class)->getRates();
        $cryptoAmounts = DB::table('assets')->select('crypto', 'amount')->where( 'user_id', $userId )->get();

        $total = 0;

        foreach($cryptoAmounts as $cryptoAmount)
        {
            $crypto = $cryptoAmount->crypto;
            $total += $cryptoAmount->amount * $rates[ $crypto ];
        }

        return $total;
    }

    public static function calculatePrice($crypto, $amount)
    {
        $cryptoApi = app()->make(CryptoApi::class);
        $rates = $cryptoApi->getRates();

        return $rates[$crypto] * $amount;
    }
}
