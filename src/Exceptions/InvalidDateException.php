<?php
/**
 * Esta clase que extiende de "ValidationException" devuelve el mensaje "Error: fecha inválida"
 */
class InvalidDateException extends ValidationException
{
    public function __construct($message = "Error: fecha inválida", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message,  $code, $previous);
    }
}