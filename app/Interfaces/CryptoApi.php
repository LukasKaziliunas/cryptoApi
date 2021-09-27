<?php

namespace App\Interfaces;

interface CryptoApi
{
    const CRYPTOS = ['BTC', 'ETH', 'DOGE'];

    public function getRates();

    public function makeApiCall();

    public function parseResponseToPricesArray($jsonResponse);
}
