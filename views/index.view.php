<?php

/**
 * Este archivo se encarga de mostrar una página con una LISTA de todas las películas que se encuentran en la
 * array de objetos @movies, como en la array hay objetos de tipo @Movie podemos obtener sus propiedades como el "id".
 * Con el "id" de cada objeto @Movie de la array @movies establecemos un ENLACE a cada película y le pasamos por la
 * url el "id" de la misma para luego obtenerlo y saber que película mostrar, ya que el "id" es único.
 */

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Rubén Romera">
    <title>Movies</title>
</head>
<body>
<!--<pre>
    <?/*=print_r($movies)*/?>
</pre>-->
    <h1>Movies - Rubén Romera</h1>
    <h3><a href="movies_create.php">Insertar nueva película</a></h3>
    <ul>
        <?php foreach($movies as $pelicula):
            /** Establecemos un enlace a cada elemento de la lista con el "id" de su película correspondiente */?>
            <li><a href="pelicula.php?id=<?=$pelicula->getId()?>"><?=$pelicula->getTitle()?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
