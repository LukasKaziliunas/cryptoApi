<?php

namespace App\Managers\Crypto\Drivers;

use App\Managers\Crypto\Contracts\Driver;
use GuzzleHttp;

class CoinMarketCapDriver implements Driver
{

    protected $config;
    protected $cryptos;

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
     * @return stdObject
     */
    private function makeApiCall()
    {
        $cryptosListString = implode(",", $this->cryptos); // "BTC,ETH"  ...

        $cacheTime = $this->calculateCacheTime();

        $urlQuery = '?symbol=' . $cryptosListString . '&convert=usd';

        $response = cache()->remember('coinmarketcap', $cacheTime, function () use ($urlQuery) {
            return $this->sendRequest($this->config['key'], $urlQuery);
        });
        return $response;
    }

    /**
     * Return an array of cryptos and their prices.
     *
     * @return array
     */
    private function parseResponseToPricesArray($jsonResponse)
    {
        $cryptosPrices = [];

        foreach ($this->cryptos as $cryptoSymbol) {
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
    private function sendRequest($key, $url)
    {
        $response = $this->getClient()->request('GET', $url, ['headers' => ['X-CMC_PRO_API_KEY' => $key]]);

        return json_decode($response->getBody()->getContents()); // konvertuoja json response i stdClass objekta
    }

    private function getClient()
    {
        return new GuzzleHttp\Client([
            'base_uri' => $this->config['url'],
        ]);
    }

    private function calculateCacheTime()
    {
        $minutesInDay = 1440;
        $cacheDuration = floor($minutesInDay / $this->config['limit']);
        $cacheTime = now()->addMinutes($cacheDuration);
        return $cacheTime;
    }
}
