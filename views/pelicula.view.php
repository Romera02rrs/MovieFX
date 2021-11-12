<?php declare(strict_types=1);

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
    <h2>Acciones</h2>
    <ul>
        <li><a href="../pelicula-edit.php?id=<?=$movie->getId()?>"><button>Editar</button></a></li>
        <li><a href="pelicula-delete.php?id=<?=$movie->getId()?>"><button>Borrar</button></a></li>
    </ul>
    <br>
    <footer>
        <hr>
        <p><a href="index.php">Volver a la página de Inicio</a></p>
    </footer>
<?php else:?>

    <h2>Errores</h2>
    <p><?=print_r($errores)?></p>
    <br>
    <footer>
        <hr>
        <p><a href="index.php">Volver a la página de Inicio</a></p>
    </footer>
<?php endif; ?>
</body>
</html>
