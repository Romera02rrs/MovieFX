<?php

require "movies.inc.php";

$id = 0;
$errores = [];
$movie = null;

$idAux = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if(!empty($idAux))
    $id = $idAux;

$pelicula = array_filter($movies, function ($movie) use ($id){
   if($movie->getId()===$id)
       return true;
   return false;
});

if (count($pelicula) === 1)
    $movie = array_shift($pelicula);
else
    $errores[] = "La película no ha sido encontrada";

require "views/pelicula.view.php";