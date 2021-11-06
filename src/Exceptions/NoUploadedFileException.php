<?php
/**
 * Esta clase que extiende de "FileUploadException" devuelve el mensaje "Error: el archivo no ha sido subido"
 */
class NoUploadedFileException extends FileUploadException
{
    public function __construct($message = "Error: el archivo no ha sido subido", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message,  $code, $previous);
    }
}