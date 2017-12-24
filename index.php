<?php

use \xvilo\Movieskoop\Movieskoop;
use \xvilo\Movieskoop\Movie;

include 'vendor/autoload.php';

$movieskoop = new Movieskoop();

$allMovies = $movieskoop->getMovie('/movies/1525/17/jumanji_welcome_to_the_jungle_3d');

die(var_dump($allMovies));