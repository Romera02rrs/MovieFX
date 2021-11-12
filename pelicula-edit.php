<?php

declare(strict_types=1);

require_once 'src/Movie.php';
require "helpers.php";

/**
 * ???
 */
if (isPost()) {
    $idTemp = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
}else {
    $idTemp = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
}

if (!empty($idTemp)) {
    $id = $idTemp;
}else {
    throw new Exception("Id Invalid");
}

$pdo = new PDO("mysql:host=localhost;dbname=MoviesFX;charset=utf8", "dbuser", "1234");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$moviesStmt = $pdo->prepare("SELECT * FROM movie WHERE id=:id");
$moviesStmt->bindValue("id", $id);
$moviesStmt->setFetchMode(PDO::FETCH_ASSOC);
$moviesStmt->execute();

$data = $moviesStmt->fetch();

$validTypes = ["image/jpeg", "image/jpg"];

$errors = [];

if (isPost()) {

    $idTemp = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if (!empty($idTemp)) {
        $data["id"] = $idTemp;
    }else {
        throw  new Exception("Invalid ID");
    }

    try {
        if (validate_string($_POST["title"], 2,  100)) {
            $data["title"] = clean($_POST["title"]);
        }
    } catch (ValidationException $e) {
        $errores[] =  $e->getMessage();
    }

    try {
        if (validate_string($_POST["overview"], 2, 1000)) {
            $data["overview"] = clean($_POST["overview"]);
        }
    } catch (ValidationException $e) {
        $errores[] = $e->getMessage();
    }

    try {
        if (validate_date($_POST["release_date"])) {
            $data["release_date"] = clean($_POST["release_date"]);
        }
    }catch (ValidationException $e) {
        $errores[] = $e->getMessage();
    }

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

    try {
        if ($filePath = validate_file($_FILES["poster"])){
            //$data["poster"] = $_FILES["poster"]["name"];
            $data["poster"] = $filePath;
        }
    }catch (FileUploadException $e){
        $errores[] = $e->getMessage();
    }
}

if (isPost() && empty($errors)) {

    $pdo = new PDO("mysql:host=localhost;dbname=MoviesFX;charset=utf8", "dbuser", "1234");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');


    $moviesStmt = $pdo->prepare("
                            UPDATE movie 
                            set title = :title, 
                                overview = :overview, 
                                release_date =:release_date, 
                                rating = :rating, 
                                poster=:poster
                            WHERE id = :id");

    $moviesStmt->execute($data);

    if ($moviesStmt->rowCount() !== 1) {
        $errores[] = "Error al insertar la película";
    } else {
        $message = "La película ha sido insertada cn el ID: ({$data["id"]})";
    }
}

$movie = new Movie();
$movie->setId((int)$data["id"]);
$movie->setTitle($data["title"]);
$movie->setPoster($data["poster"]);
$movie->setReleaseDate($data["release_date"]);
$movie->setOverview($data["overview"]);
$movie->setRating((float)$data["rating"]);

require "views/movies-edit.view.php";