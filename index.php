<?php declare(strict_types=1);

/**
 * Este archivo se encarga de obtener todos los objetos de tipo @Movie desde la base de datos y almacenarlos en
 * una array de objetos llama @movies de tipo @Movie.
 */

require "src/Movie.php";                                                        // Se llama a la clase Movie para crear objetos de tipo movie.
require "src/User.php";

$conexion = "mysql:host=localhost;dbname=MoviesFX;charset=utf8";
$pdo = new PDO($conexion, "dbuser", "1234");                   // Se crea una conexión a la base de datos mediante el usuario dbuser.
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // Para que muestre los errores que tenga PDO.

$moviesStmt = $pdo->prepare("SELECT * FROM movie");                        // Se prepara una consulta que obtendrá todas las películas de la base de datos.
$moviesStmt->setFetchMode(PDO::FETCH_ASSOC);                               // Se indica que lo que devuelva la consulta sea un array asociativo.
$moviesStmt->execute();                                                          // Se ejecuta la consulta.

$moviesAr = $moviesStmt->fetchAll();                                             // Indicamos que vamos a obtener varios campos de la consulta y los almacenamos en una array.

foreach ($moviesAr as $movieAr) {                                                // Recorremos todos los campos de la array.
    $movie = getMovies($movieAr);                                                // La función requiere una array y devuelve un objeto Movie con los datos de la array.
    $movies[] = $movie;                                                          // Almacena los objetos en una array de objetos Movie.
}

function getMovies(Array $movieAr): Movie
{
    $movie = new Movie();                                                        // Creamos un objeto de tipo Movie temporalmente vacío.
    $movie->setId((int)$movieAr["id"]);                                          // Casteamos a un entero porque la base de datos siempre devuelve strings.
    $movie->setTitle($movieAr["title"]);
    $movie->setPoster($movieAr["poster"]);
    $movie->setReleaseDate($movieAr["release_date"]);
    $movie->setOverview($movieAr["overview"]);
    $movie->setRating((float)$movieAr["rating"]);
    return $movie;                                                               // Devolvemos el objeto movie con los datos insertados.
}

/*echo "La película {$movie->getTitle()} tiene una valoración de {$movie->getRating()}";

$user = new User(2, "juan");

$value = 3;

echo "<p>El usuario {$user->getUsername()} la valora con $value puntos</p>";

$user->rate($movie, $value);

echo "<p>La película {$movie->getTitle()} ahora tiene una valoración de {$movie->getRating()} puntos</p>";*/

require "movies.inc.php";                                                        // Llamamos a al fichero "movies.inc.php" para insertar películas manualmente.

require "views/index.view.php";                                                  // Llamamos al fichero "views/index.view.php" una lista de películas.