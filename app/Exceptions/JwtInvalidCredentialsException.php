<?php

namespace App\Exceptions;

use Exception;

class JwtInvalidCredentialsException extends Exception
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'Invalid Credentials';
}
