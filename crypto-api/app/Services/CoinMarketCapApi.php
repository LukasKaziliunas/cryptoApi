<?php

namespace App\Services;

use \App\Interfaces\CryptoApi;
use Illuminate\Support\Facades\Http;

class CoinMarketCapApi implements CryptoApi
{

    private $key; 
    private $limit;

    public function __construct()
    {
         $this->key = config("crypto.coinmarketcap.key");
         $this->limit = config("crypto.coinmarketcap.limit");
    }

    public function getRates()
    {
        $response = $this->makeApiCall();
        return $this->parseResponseToPricesArray($response);
    }

    public function makeApiCall()
    {
        $cryptosListString = implode(",", self::CRYPTOS); // "BTC,ETH"  ...
        $minutesInDay = 1440;
        $cacheDuration = floor($minutesInDay / $this->limit);
        
        $response = cache()->remember('coinmarketcap', now()->addMinutes($cacheDuration), function() use($cryptosListString){
            return json_decode( $this->sendRequest($this->key, $cryptosListString) );
        });
       
        return $response;
    }

    public function parseResponseToPricesArray($jsonResponse)
    {
        $cryptosPrices = [];
        
        foreach(self::CRYPTOS as $cryptoSymbol)
        {
            $price = $jsonResponse->data->$cryptoSymbol->quote->USD->price;
    
            $cryptosPrices[$cryptoSymbol] = $price;
        }

        return $cryptosPrices;
    }

    private function sendRequest($key, $cryptosListString)
    {
        return Http::acceptJson()->withHeaders([
            'X-CMC_PRO_API_KEY' => $key,
        ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol='. $cryptosListString .'&convert=usd');
    }

}