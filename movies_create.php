<?php
declare(strict_types=1);

/**
 * Este archivo se encarga de comprobar y validar todos los campos del formulario, asi como todos los datos de entrada
 * del usuario, primero comprueba que el formulario haya sido enviado y luego llama a las funciones correspondientes
 * para que se encarguen de validarlo.
 */

// Use la sintaxi alternativa de les estructures de control per a la part de vistes. ??

require "helpers.php";
require_once 'src/Movie.php';

/**
 * Se inicializan las variables para que existan en todos los posibles caminos y poder gurdar el "Sticky Form".
 * Los datos que obtendrán los campos del formulario se almacenarán en una array asociativa.
 * Solo se almacenan valores válidos.
 */
$data["title"] = "";
$data["release_date"] = "";
$data["overview"] = "";
$data["poster"] = "";
$data["rating"] = 0;

$errores = [];

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
        $errores[] =  $e->getMessage();
    }

    /**
     * Validación del campo "overview".
     */
    try {
        if (validate_string($_POST["overview"], 2, 1000)) {
            $data["overview"] = clean($_POST["overview"]);
        }
    } catch (ValidationException $e) {
        $errores[] = $e->getMessage();
    }

    /**
     * Validación del campo "release_date". Si no es válido lanza excepciones.
     */
    try {
        if (validate_date($_POST["release_date"])) {
            $data["release_date"] = clean($_POST["release_date"]);
        }
    }catch (ValidationException $e) {
        $errores[] = $e->getMessage();
    }

    /**
     * Obtiene el campo rating, verifica que tenga un entero y lo almacena en la variable @$ratingTemp.
     * Si no tiene valor le asigna -1 para poder enviárselo por parámetros a "validate_ratingTemp" y que no de error,
     * Si "validate_ratingTemp" no cumple con las verificaciones, lanza una excepción que se almacenará su mensaje
     * en el array @$errores.
     */
    try {
        $ratingTemp = filter_input(INPUT_POST, "rating", FILTER_VALIDATE_INT);
        if(!$ratingTemp){
            $ratingTemp = -1;
        }
        if (validate_ratingTemp($ratingTemp)){
            $data["rating"] = $ratingTemp;
        }
    }catch (ValidationException $e){
        $errores[] = $e->getMessage();
    }

    /**
     * Se encarga de obtener un archivo y almacenar sus datos en una array asociativa. Envía la array y valida que
     * todos los datos sean correctos, si no lo son, lanza excepciones de tipo "FileUploadException".
     */
    try {
        if ($filePath = validate_file($_FILES["poster"])){
            //$data["poster"] = $_FILES["poster"]["name"];
            $data["poster"] = $filePath;
        }
    }catch (FileUploadException $e){
        $errores[] = $e->getMessage();
    }
}

/**
 * Si el formulario ha sido enviado y además no contiene errores, se prepara y ejecuta una consulta a la base de datos
 * donde se almacenan los datos del formulario.
 */
if (isPost() && empty($errores)) {
    $pdo = new PDO("mysql:host=localhost;dbname=MoviesFX;charset=utf8", "dbuser", "1234");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');


    $moviesStmt = $pdo->prepare("INSERT INTO movie(title, overview, release_date, rating, poster) 
        VALUES (:title, :overview, :release_date, :rating, :poster)");

    $moviesStmt->debugDumpParams();
    $moviesStmt->execute($data);

    if ($moviesStmt->rowCount() !== 1)
        $errores[] = "No s'ha pogut inserir el registre";
    else
        $message = "S'ha inserit el registre amb el ID ({$pdo->lastInsertId("movie")})";
}

require "views/movies_create.view.php";