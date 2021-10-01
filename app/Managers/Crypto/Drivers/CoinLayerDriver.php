<?php

namespace App\Managers\Crypto\Drivers;

use App\Managers\Crypto\Contracts\Driver;
use GuzzleHttp;

class CoinLayerDriver implements Driver
{
    protected $config;
    protected $cryptos;

    /**
     * Create a new driver.
     *
     * @param array $config
     * @param array $cryptos
     * @return void
     */
    public function __construct(array $config, array $cryptos)
    {
        $this->config = $config;
        $this->cryptos = $cryptos;
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
        $cryptosListString = implode(",", $this->cryptos); // "BTC,ETH"

        $cacheTime = $this->calculateCacheTime();

        $urlQuery = "?access_key=" . $this->config['key'] . "&symbols=" . $cryptosListString;

        $response = cache()->remember('coinlayer', $cacheTime, function () use ($urlQuery) {
            return $this->sendRequest($urlQuery);
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
    private function sendRequest($url)
    {
        $response = $this->getClient()->request('GET', $url);

        return json_decode($response->getBody()->getContents(), true); //konvertuoja json string i rakto-reiksmes masyva
    }

    /**
     * Calculates until what time from now the response should be cached.
     *
     * @return Illuminate\Support\Carbon
     */
    protected function calculateCacheTime()
    {
        $minutesInDay = 1440;
        $cacheDuration = floor($minutesInDay / $this->config['limit']);
        $cacheTime = now()->addMinutes($cacheDuration);
        return $cacheTime;
    }

    /**
     * Returns guzzle client with base url set up.
     *
     * @return GuzzleHttp\Client
     */
    protected function getClient()
    {
        return new GuzzleHttp\Client([
            'base_uri' => $this->config['url'],
        ]);
    }
}
