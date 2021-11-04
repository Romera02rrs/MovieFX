<?php

/**
 * Este archivo se encarga de Crear objetos de tipo @Movie y establecerle valores manualmente sin usar formularios
 * ni bases de datos, guarda los objetos de tipo @Movie en la array común de objetos @movies.
 */

$movie = new Movie();
$movie->setId(1);
$movie->setTitle("Eternals");
$movie->setPoster("Eternals.jpg");
$movie->setReleaseDate("2021-11-5");
$movie->setOverview("Los Eternos son una raza de seres inmortales con poderes sobrehumanos que han vivido en 
    secreto en la Tierra durante miles de años. Aunque nunca han intervenido, ahora una amenaza se cierne sobre la 
    humanidad.");
$movie->setRating(3.80);

/*-------------------------------------------------------------------------------------------------------------------*/

$movies[] = $movie;

$movie = new Movie();
$movie->setId(2);
$movie->setTitle("Venom: Let There Be Carnage");
$movie->setPoster("Venom.jpg");
$movie->setReleaseDate("2021-10-1");
$movie->setOverview("Después de encontrar un cuerpo anfitrión en el periodista de investigación Eddie Brock, el 
    simbionte alienígena debe enfrentarse a un nuevo enemigo, Carnage, el alter ego del asesino en serie Cletus Kasady.
");
$movie->setRating(4.20);

/*-------------------------------------------------------------------------------------------------------------------*/

$movies[] = $movie;

$movie = new Movie();
$movie->setId(3);
$movie->setTitle("Ready Player One");
$movie->setPoster("Ready_Player_One.jpg");
$movie->setReleaseDate("2018-3-28");
$movie->setOverview("Año 2045: el adolescente Wade Watts es solo una de las millones de personas que se evaden
    del sombrío mundo real para sumergirse en un mundo utópico virtual donde todo es posible: OASIS. Wade participa en 
    la búsqueda del tesoro que el creador de este mundo imaginario dejó oculto en su obra. No obstante, hay gente muy 
    peligrosa compitiendo contra él.");
$movie->setRating(4.60);

$movies[] = $movie;