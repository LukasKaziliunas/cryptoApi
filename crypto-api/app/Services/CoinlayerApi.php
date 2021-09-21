<?php

namespace App\Services;

use \App\Interfaces\CryptoApi;
use Illuminate\Support\Facades\Http;

class CoinlayerApi implements CryptoApi
{
    private $key; 
    private $limit;

    public function __construct()
    {
         $this->key = config("crypto.coinlayer.key");
         $this->limit = config("crypto.coinlayer.limit");
    }

    public function getRates()
    {
        $response = $this->makeApiCall();
        return $this->parseResponseToPricesArray($response);
    }

    public function makeApiCall()
    {
        $cryptosListString = implode(",", self::CRYPTOS); // "BTC,ETH"
        $minutesInDay = 1440;
        $cacheDuration = floor($minutesInDay / $this->limit);

        $response = cache()->remember('coinlayer', now()->addMinutes($cacheDuration), function() use($cryptosListString){
            return $this->sendRequest( $this->key, $cryptosListString );
        });
        return $response;
    }

    public function parseResponseToPricesArray($jsonResponse)
    {
        return $jsonResponse->rates;
    }

    private function sendRequest($key, $cryptosListString)
    {
        $response = Http::acceptJson()
                ->get("http://api.coinlayer.com/api/live?access_key={$key}&symbols={$cryptosListString}");

        $response->throw(); //jei atsirastu klaida išmes exception

        return json_decode( $response );
    }

}