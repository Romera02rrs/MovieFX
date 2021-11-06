<?php
/**
 * Es la superclase de las validaciones de archivos y todas las validaciones de archivos extienden de esta.
 * Devuelve "Error en el archivo" cuando es llamada.
 */

class FileUploadException extends Exception
{
    public function __construct($message = "Error en el archivo", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}