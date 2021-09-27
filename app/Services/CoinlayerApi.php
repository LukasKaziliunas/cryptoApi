<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use \App\Interfaces\CryptoApi;

class CoinlayerApi implements CryptoApi
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
        $this->key = config("crypto.coinlayer.key");
        $this->limit = config("crypto.coinlayer.limit");
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
     * @return array
     */
    public function makeApiCall()
    {
        $cryptosListString = implode(",", self::CRYPTOS); // "BTC,ETH"
        $minutesInDay = 1440;
        $cacheDuration = floor($minutesInDay / $this->limit);
        $cacheTime = now()->addMinutes($cacheDuration);

        $response = cache()->remember('coinlayer', $cacheTime, function () use ($cryptosListString) {
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
        return $jsonResponse['rates'];
    }

    /**
     * Sends an HTTP request to external api.
     *
     * @return array
     */
    private function sendRequest($key, $cryptosListString)
    {
        $response = Http::acceptJson()
            ->get("http://api.coinlayer.com/api/live?access_key={$key}&symbols={$cryptosListString}");

        $response->throw(); //jei atsirastu klaida išmes exception

        return json_decode($response, true); //konvertuoja json string i rakto-reiksmes masyva
    }
}
