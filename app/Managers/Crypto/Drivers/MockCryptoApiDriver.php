<?php

namespace App\Managers\Crypto\Drivers;

use App\Managers\Crypto\Contracts\Driver;

class MockCryptoApiDriver implements Driver
{
    /**
     * Returns mock crypto currencys rates.
     * @return array rates
     */
    public function getRates()
    {
        return json_decode(file_get_contents(base_path('resources/mocks/cryptoApiResponse.json')), true);
    }
}
