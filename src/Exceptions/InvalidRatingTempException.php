<?php
/**
 * Esta clase que extiende de "ValidationException" devuelve el mensaje "Error: rating inválido"
 */
class InvalidRatingTempException extends ValidationException
{
    public function __construct($message = "Error: rating inválido", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message,  $code, $previous);
    }
}