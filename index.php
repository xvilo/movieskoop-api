<?php

use \xvilo\Movieskoop\Movieskoop;
use \xvilo\Movieskoop\Movie;

include 'vendor/autoload.php';

$movieskoop = new Movieskoop();

$allMovies = $movieskoop->getMovies();

$movie = new Movie($allMovies[1]['id']);
die(var_dump($movie));