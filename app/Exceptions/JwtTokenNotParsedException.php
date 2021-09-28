<?php

namespace App\Exceptions;

use Exception;

class JwtTokenNotParsedException extends Exception
{
    /**
     * {@inheritdoc}
     */
    protected $message = 'Token not parsed';
}
