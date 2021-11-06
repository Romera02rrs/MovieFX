<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Rubén Romera">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Movies</title>
</head>

<body>
<h1>Nueva película</h1>
<?php if (!isPost() || !empty($errors)) :?>
    <form action="" method="post" enctype="multipart/form-data">
        <pre>
        <?php
        if (!empty($errors))
            print_r($errors)
        ?>
        </pre>
        <div>
            <label for="title">Title</label><br>
            <input id="title" type="text" name="title" value="<?= $data["title"] ?>"><br><br>
        </div>
        <div>
            <label for="release-date">Release date (YYYY-mm-dd)</label><br>
            <input id="title" type="text" name="release_date" value="<?= $data["release_date"] ?>"><br><br>
        </div>
        <div id="rata">
            <p>Rating</p>
            <?php foreach ([1, 2, 3, 4, 5] as $ratingValue) : ?>
                <label for="genre<?= $ratingValue ?>">
                    <input id="genre<?= $ratingValue ?>" type="radio" name="rating"
                           value="<?= $ratingValue ?>" <?= ($data["rating"] === $ratingValue) ? "checked":"" ?> >
                    <?= $ratingValue ?>
                </label>
            <?php endforeach ?>
        </div>
        <div>
            <label for="overview">Overview</label><br>
            <textarea id="overview" name="overview"><?= $data["overview"] ?></textarea><br><br>
        </div>
        <div>
            <label for="poster">Poster</label><br>
            <input id="poster" type="file" name="poster" /><br><br>
        </div>
        <div>
            <input type="submit" value="Crear">
        </div>
    </form>
<?php endif; ?>
<?php if (empty($errors) && isPost()) : ?>
    <h2><?=$message?></h2>
    <table>
        <tr>
            <th>Title</th>
            <td><?= $data["title"] ?></td>
        </tr>
        <tr>
            <th>Overview</th>
            <td><?= $data["overview"] ?></td>
        </tr>
        <tr>
            <th>Release date</th>
            <td><?= date("d/m/Y", strToTime($data["release_date"])) ?></td>
        </tr>
        <tr>
            <th>Rating</th>
            <td><?= $data["rating"] ?></td>
        </tr>
    </table>
<?php endif ?>
</body>

</html>