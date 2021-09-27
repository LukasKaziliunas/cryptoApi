<?php

namespace App\Services;

use \App\Interfaces\CryptoApi;
use Illuminate\Support\Facades\Http;

class CoinMarketCapApi implements CryptoApi
{

    /**
     * secret key of the external api.
     *
     * @var string
     */
    private $key; 

     /**
     * daily request limit for external api.
     *
     * @var int
     */
    private $limit;

    public function __construct()
    {
         $this->key = config("crypto.coinmarketcap.key");
         $this->limit = config("crypto.coinmarketcap.limit");
    }

     /**
     * Return an array of cryptos and their prices.
     *
     * @return array
     */
    public function getRates()
    {
        $response = $this->makeApiCall();
        return $this->parseResponseToPricesArray($response);
    }

     /**
     * Makes a call to external crypto api if response is not cached and returns a raw response.
     *
     * @return stdObject
     */
    public function makeApiCall()
    {
        $cryptosListString = implode(",", self::CRYPTOS); // "BTC,ETH"  ...
        $minutesInDay = 1440;
        $cacheDuration = floor($minutesInDay / $this->limit);
        
        $response = cache()->remember('coinmarketcap', now()->addMinutes($cacheDuration), function() use($cryptosListString){
            return $this->sendRequest($this->key, $cryptosListString);
        });
       
        return $response;
    }

     /**
     * Return an array of cryptos and their prices.
     *
     * @return array
     */
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

     /**
     * Sends an HTTP request to external api.
     *
     * @return stdObject
     */
    private function sendRequest($key, $cryptosListString)
    {
        $response = Http::acceptJson()->withHeaders([
            'X-CMC_PRO_API_KEY' => $key,
        ])->get('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol='. $cryptosListString .'&convert=usd');

        $response->throw(); //jei atsirastu klaida išmes exception

        return json_decode( $response ); // konvertuoja json response i stdClass objekta
    }

}