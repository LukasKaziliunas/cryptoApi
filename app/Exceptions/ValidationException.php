<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
    private $errors = null;
    private $fields = null;

    public function __construct($errors, $fields, $message = '')
    {
        $this->errors = $errors;
        $this->fields = $fields;
        parent::__construct($message);
    }

    /**
     * @return array error messages
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array fields and inputed values
     */
    public function getFields()
    {
        return $this->fields;
    }
}
