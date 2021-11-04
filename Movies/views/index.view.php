<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Rubén Romera">
    <title>Movies</title>
</head>
<body>
    <h1>Movies - Rubén Romera</h1>
    <ul>
        <?php foreach($movies as $pelicula):?>
            <li><a href="pelicula.php?id=<?=$pelicula->getId()?>"><?=$pelicula->getTitle()?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
