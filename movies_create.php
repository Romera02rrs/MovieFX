<?php
declare(strict_types=1);

/**
 * Este archivo se encarga de comprobar y validar todos los campos del formulario, asi como todos los datos de entrada
 * del usuario, primero comprueba que el formulario haya sido enviado y luego llama a las funciones correspondientes
 * para que se encarguen de validarlo.
 */

// Inicialitze les variables perquè existisquen en tots els possibles camins
// Sols emmagatzameré en elles valors vàlids.
// Acumularé els errors en un array per a mostrar-los al final.
// Use la sintaxi alternativa de les estructures de control per a la part de vistes.
// Cree funció clean per a netejar valors

require "helpers.php";
require 'src/Exceptions/FileUploadException.php';
require_once 'src/Exceptions/NoUploadedFileException.php';
require_once 'src/Movie.php';

const MAX_SIZE = 1024*1000;

/**
 * Se inicializan las variables para que existan en todos los posibles caminos y poder gurdar el "Sticky Form".
 * Los datos que obtendrán los campos del formulario se almacenarán en una array asociativa.
 */
$data["title"] = "";
$data["release_date"] = "";
$data["overview"] = "";
$data["poster"] = "";
$data["rating"] = 0;


$validTypes = ["image/jpeg", "image/jpg"];

$errors = [];

/**
 * Si el formulario ha sido enviado procedemos a validar los campos necesarios. Comprueba si se ha enviado mediante
 * la función "isPost()" que se encuentra en el archivo "helpers.php"
 */
if (isPost()) {

    /**
     * Validación del campo "title".
     * LLamo a la función validate_string que se encuentra en "helpers.php" y le paso por parámetros el string que he
     * obtenido del campo "title", el número mínimo de caracteres y por último el maximo.
     * La función "validate_string" devuelve true si es válido o por lo contrario lanza una excepción que es capturada
     * dentro del bloque try-catch.
     * Si es válido obtiene el valor del formulario y lo pasa por la función "clean" antes de almacenarlo en la array
     * de datos.
     */
    try {
        if (validate_string($_POST["title"], 2,  100)) {
            $data["title"] = clean($_POST["title"]);
        }
    } catch (ValidationException $e) {
        $errors[] =  $e->getMessage();
    }

    /**
     * Validación del campo "overview".
     */
    try {
        if (validate_string($_POST["overview"], 2, 1000)) {
            $data["overview"] = clean($_POST["overview"]);
        }
    } catch (ValidationException $e) {
        $errors[] = $e->getMessage();
    }

    /**
     * Validación del campo "release_date". Si no es válido lanza excepciones.
     */
    try {
        if (validate_date($_POST["release_date"])) {
            $data["release_date"] = clean($_POST["release_date"]);
        }
    }catch (ValidationException $e) {
        $errors[] = $e->getMessage();
    }

    $ratingTemp = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_FLOAT); // = if($_POST["rating"] == type float) ¿FLOAT?

    if (!empty($ratingTemp) && ($ratingTemp > 0 && $ratingTemp <= 5))
        $data["rating"] = $ratingTemp;
    else
        $errors[] = "El rating ha de ser un enter entre 1 i 5";

    try {
        if (!empty($_FILES['poster']) && ($_FILES['poster']['error'] == UPLOAD_ERR_OK)) { // ?
            if (!file_exists(Movie::PATH_POSTERS))
                mkdir(Movie::PATH_POSTERS, 0777, true);

            $tempFilename = $_FILES["poster"]["tmp_name"];
            $currentFilename = $_FILES["poster"]["name"];

            $mimeType = getFileExtension($tempFilename);

            $extension = explode("/", getFileExtension($tempFilename))[1];
            $newFilename = md5((string)rand()) . "." . $extension;
            $newFullFilename = Movie::PATH_POSTERS . "/" . $newFilename;
            $fileSize = $_FILES["poster"]["size"];

            if (!in_array($mimeType, $validTypes))
                throw new InvalidTypeFileException("La foto no és jpg"); // Throw

            if ($extension != 'jpeg')
                throw new InvalidTypeFileException("La foto no és jpg"); // 2 veces

            if ($fileSize > MAX_SIZE)
                throw new TooBigFileException("La foto té $fileSize bytes");

            if (!move_uploaded_file($tempFilename, $newFullFilename))
                throw new FileUploadException("No s'ha pogut moure la foto");

            $data["poster"] = $newFilename;

        } else
            throw new NoUploadedFileException("Cal pujar una photo");
    } catch (FileUploadException $e) {
        $errors[] = $e->getMessage();
    }
}

if (isPost() && empty($errors)) {
    $pdo = new PDO("mysql:host=localhost;dbname=MoviesFX;charset=utf8", "dbuser", "1234");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');


    $moviesStmt = $pdo->prepare("INSERT INTO movie(title, overview, release_date, rating, poster) 
        VALUES (:title, :overview, :release_date, :rating, :poster)");

    $moviesStmt->debugDumpParams();
    $moviesStmt->execute($data);

    if ($moviesStmt->rowCount() !== 1)
        $errors[] = "No s'ha pogut inserir el registre";
    else
        $message = "S'ha inserit el registre amb el ID ({$pdo->lastInsertId("movie")})";
}

require "views/movies_create.view.php";