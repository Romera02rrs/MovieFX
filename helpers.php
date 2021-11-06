<?php

require_once "src/Exceptions/ValidationException.php";
require_once "src/Exceptions/RequiredValidationException.php";
require_once "src/Exceptions/TooShortValidationException.php";
require_once "src/Exceptions/TooLongValidationException.php";
require_once "src/Exceptions/InvalidDateException.php";
require_once "src/Exceptions/InvalidRatingTempException.php";

require_once "src/Exceptions/FileUploadException.php";
require_once "src/Exceptions/NoUploadedFileException.php";
require_once "src/Exceptions/InvalidTypeFileException.php";
require_once "src/Exceptions/TooBigFileException.php";

const MAX_SIZE = 1024*1000;

/**
 * @param string $valor
 * Limpia la cadena de texto que recibe, trim(Elimina espacios en blanco delante y detras del string),
 * htmlspecialchars(impide que se ejecute código html como <b>).
 * @return string
*/
function clean(string $valor): string
{
    $valor = trim($valor);
    return htmlspecialchars($valor);
}

/**
 * @return boolean
 */
function isPost(): bool
{
    return $_SERVER["REQUEST_METHOD"] === "POST";
}

/**
 * @param string $string
 * @param int $minLength
 * @param int $maxLength
 * Se encarga de obtener un string, y dos enteros que determinan lan longitud del string, si no cumple con las
 * validaciones devuelve excepciones. Y si cumple con las validaciones devuelve "true".
 * @throws TooShortValidationException
 * @throws TooLongValidationException
 * @throws RequiredValidationException
 * @return true
 */
function validate_string(string $string, int $minLength = 1, int $maxLength = 50000): bool
{
    if (empty($string))
        throw new RequiredValidationException();
    if (strlen($string) < $minLength)
        throw new TooShortValidationException();
    if (strlen($string) > $maxLength)
        throw new TooLongValidationException();

    return true;
}
/**
 * @param int $rating
 * Obtiene un número, si está vació devuelve una excepción y si es menor que 0 o mayor que 5 devuelve otra excepción.
 * @return true
 *@throws InvalidRatingTempException
 */
function validate_ratingTemp(int $rating = -1): bool
{
    if (empty($rating) || $rating === -1){
        throw new InvalidRatingTempException();
    }
    if ($rating < 0 || $rating > 5){
        throw new InvalidRatingTempException();
    }
    return true;
}

/**
 * @param string $date
 * Se encarga de validar la fecha que se introduce en el formulario, si no consigue darle el formato "Y-m-d", lanza
 * una excepción, si "DateTime" tiene algún error lanza otra excepción. Si la fecha es válida devuelve "true".
 * @return true
 *@throws InvalidDateException
 * @throws RequiredValidationException
 */
function validate_date(string $date): bool
{

    if(empty($date)){
        throw new RequiredValidationException();
    }
    if (DateTime::createFromFormat("Y-m-d", $date)===false){
        throw new InvalidDateException();
    }

    $errors = DateTime::getLastErrors();

    //echo var_dump($errors);
    if (count($errors["warnings"])>0 || count($errors["errors"])>0){
        throw new InvalidDateException();
    }

    return true;
}

/**
 * @param string $filename
 * El método se encarga de verificar que el archivo que se pasa por parámetros sea realmente del tipo que dice ser.
 * @throws InvalidTypeFileException
 * @return string
 */
function getFileExtension(string $filename): string
{
    $mime = "";
    try {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $filename);
        if ($mime === false) {
            throw new InvalidTypeFileException();
        }
    } finally {
        finfo_close($finfo);
    }
    return $mime;
}

/**
 * @param array $fileAr
 * Este método se encarga de validar el archivo que recibe por parámetros, lo primero es comprobar que se halla
 * subido algún archivo. Obtiene por parámetros una array con toda la información del archivo y almacena en variables
 * los datos que se vayan a utilizar.
 * LLama a la función @getfileExtension para obtener el tipo de archivo y comprobar que es del tipo que dice ser.
 * Devuelve excepciones de tipo @FileUploadException si no cumple con alguna validación.
 * @throws NoUploadedFileException
 * @throws FileUploadException
 * @throws InvalidTypeFileException
 * @throws TooBigFileException
 * @return true
 */
function validate_file(array $fileAr): bool
{
    if (empty($fileAr["size"])){
        throw new NoUploadedFileException();
    }
    $fileName = $fileAr["name"];                                    // Thor.jpg
    $fileTmp= $fileAr["tmp_name"];                                  // C:\xampp\tmp\phpB37E.tmp
    $fileSize = $fileAr["size"];                                    // 51186    // bytes
    $fileErr = $fileAr["error"];                                    // 0
    $mimeType = getFileExtension($fileTmp);                         // image/jpeg
    // $mimeType = $fileAr["type"];                                 // image/jpeg
    $extension = explode("/", $mimeType)[1];                // jpeg
    $hashFilename = md5((string)rand()) . "." . $extension;         // 402c030133ac861998a20de2d56d81c1.jpeg
    $newFullFilename = Movie::PATH_POSTERS . "/" . $hashFilename;   // posters/new/d8f4a9ebb970e91153108dbf62d15bf7.jpeg

    $validTypes = ["image/jpeg", "image/jpg"];

    if (empty($fileAr)){
        throw new NoUploadedFileException();
    }
    if ($fileErr != UPLOAD_ERR_OK){
        throw new FileUploadException();
    }
    if (!in_array($mimeType, $validTypes)) {
        throw new InvalidTypeFileException();
    }
    if ($fileSize > MAX_SIZE) {
        throw new TooBigFileException();
    }
    if (!move_uploaded_file($fileTmp, $newFullFilename)) {
        throw new FileUploadException();
    }
    return true;
}

function validate_phone(string $phone):bool
{
    if (!preg_match("/^\d{9}$/", $phone))
        throw new InvalidPhoneValidationException("Telèfon invàlid");

    return true;
}

function validate_email(string $email): bool
{
    if (empty(filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))) {
        throw new InvalidEmailValidationException("Invalid email");
    }
    return true;
}

// compare if the current value in the selected array
function is_selected(string $value, array $array): bool
{
    if (in_array($value, $array))
        return true;
    return false;
}

function validate_elements_in_array_keys(array $needle, array $haystack): bool
{
    $diff =  ((!array_diff_key(array_flip($needle), $haystack)));
    if (!$diff)
        throw new InvalidKeyValidationException("Invalid element");

    return $diff;
}
