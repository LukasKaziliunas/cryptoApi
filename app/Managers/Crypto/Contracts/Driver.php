<?php

namespace App\Managers\Crypto\Contracts;

interface Driver
{
    /**
     * Returns an array of crypto currencys rates.
     *
     * @return array rates
     */
    public function getRates();
}
