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
        <img alt="<?=$movie->getTitle()?>" src="<?=movie::PATH_POSTERS?>/<?=$movie->getPoster()?>" width='250px' heiht='500'>
    </figure>
    <p><?=$movie->getReleaseDate()?></p>
    <p><?=$movie->getOverview()?></p>
    <p><?=$movie->getRating()?></p>
<?php else:?>

    <p><?=print_r($errores)?></p>

<?php endif; ?>
</body>
</html>
