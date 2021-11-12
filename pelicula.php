<?php declare(strict_types=1);

/**
 * Este fichero se encarga de obtener el "id" de la película, obtiene dicho "id" de la url y la valida para saber
 * si corresponde con algún "id" de la base de datos. Luego llama al fichero "movies.inc.php" por si el "id" de la
 * película no se encuentra en la base de datos, sino que ha sido insertada manualmente. (Otra forma seria que el
 * fichero "movies.inc.php" haga una consulta de inserción a la base de datos para que todas las películas se encuentren
 * en la base de datos y no tenga que volver a llamar al archivo "movies.inc.php").
 */

//require "index.php";
require "src/Movie.php";                                                            // Llamo al la clase Movie para obtener propiedades de este objeto en la parte de la vista.
require "movies.inc.php";                                                           // Es necesario llamar al archivo "movies.inc.php" para obtener las películas que no están en la bd.

$id = 0;                                                                            // Creo la variable id, 0 = empty.
$errores = [];                                                                      // Array para guardar todos los posibles errores.
$movie = null;                                                                      // Creamos la variable movie, donde será almacenada la película encontrada.

$idAux = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);     // Obtengo el "id" de la película que viene de la url

if(!empty($idAux))                                                                  // Si he encontrado algún "id" en la url, establezco el valor de "id"
    $id = $idAux;

$pdo = new PDO("mysql:host=localhost;dbname=MoviesFX;charset=utf8", "dbuser", "1234");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$moviesStmt = $pdo->prepare("SELECT * FROM movie WHERE id=:id");               // Preparo una consulta donde obtengo todos los valores de la película que coincida con la variable "id".
$moviesStmt->bindValue("id", $id, PDO::PARAM_INT);
$moviesStmt->setFetchMode(PDO::FETCH_ASSOC);
$moviesStmt->execute();

$movieAr = $moviesStmt->fetch();                                                     // Obtengo de la consulta un único valor, ya que solo necesito la película que coincida con el "id" de la url.

if (!empty($movieAr)) {                                                              // Si el resultado de la consulta tiene algún valor.
    //$movie = getMovies($movieAr);
    $movie = new Movie();                                                            // Guardo en la variable movie un objeto de tipo movie temporalmente vacío.
    $movie->setId((int)$movieAr["id"]);                                              // Establezco las propiedades de la array de la consulta al objeto movie.
    $movie->setTitle($movieAr["title"]);
    $movie->setPoster($movieAr["poster"]);
    $movie->setReleaseDate($movieAr["release_date"]);
    $movie->setOverview($movieAr["overview"]);
    $movie->setRating((float)$movieAr["rating"]);
}
else
    $errors[] = "La película no ha sido encontrada";                                 // Si el resultado de la consulta está vacío es porque no se ha encontrado la película


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