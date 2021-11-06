<?php
/**
 * Esta clase que extiende de "FileUploadException" devuelve el mensaje
 * "Error: el archivo es demasiado grande (max: 1.024.000 bytes)"
 */
class TooBigFileException extends FileUploadException
{
    public function __construct($message = "Error: el archivo es demasiado grande (max: 1.024.000 bytes)",
                                $code = 0, Throwable $previous = null)
    {
        parent::__construct($message,  $code, $previous);
    }
}