<?php

class RequiredValidationException extends ValidationException
{
    public function __construct($message = "Error campo requerido", $code = 1, Throwable $previous = null)
    {
        parent::__construct($message,  $code, $previous);
    }
}