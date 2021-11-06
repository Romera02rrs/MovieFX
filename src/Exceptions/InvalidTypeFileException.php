<?php
/**
 * Esta clase que extiende de "FileUploadException" devuelve el mensaje "Error: el archivo no ha sido subido"
 */
class InvalidTypeFileException extends FileUploadException
{
    public function __construct($message = "Error: el archivo no es jpg", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message,  $code, $previous);
    }
}