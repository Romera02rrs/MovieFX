<?php

/**
 * Archivo que se encarga de mostrar cada una de las películas, sirve como plantilla para todas las películas,
 * Obtiene el objeto @movie del archivo "pelicula.php" y muestra sus propiedades en un documento HTML.
 * Si el objeto @movie está vacío, no muestra ninguna propiedad y pasa a mostrar la array de errores.
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
<?php if(!empty($movie)): ?>

    <h1><?= $movie->getTitle()?></h1>
    <figure>
        <img alt="<?=$movie->getTitle()?>" src="<?=Movie::PATH_POSTERS?>/<?=$movie->getPoster()?>" width='auto' height='500px'>
    </figure>
    <p><?=$movie->getReleaseDate()?></p>
    <p><?=$movie->getOverview()?></p>
    <p><?=$movie->getRating()?></p>

<?php else:?>

    <h2>Errores</h2>
    <p><?=print_r($errores)?></p>

<?php endif; ?>
</body>
</html>
