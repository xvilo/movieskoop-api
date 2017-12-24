<?php

namespace xvilo\Movieskoop;

use GuzzleHttp\Client as Guzzle;

class Movieskoop
{
    public function __construct()
    {
    }

    public function getMovies()
    {
        return $this->getRawMoviesHtml();
    }

    private function getRawMoviesHtml()
    {
        $movies = [];
        $pageHtml = Util::getPageBodyFromUrl('http://www.movieskoop.nl');
        preg_match_all('/<option value="(\/.*?)">(.*?)<\/option>/', $pageHtml, $matches, PREG_PATTERN_ORDER);

        // Check if URL count and Title count is same.
        if (count($matches[1]) === count($matches[2])) {
            // Loop through all the matches
            for ($x = 0; $x <= count($matches[1]) - 1; $x++) {
                // Add the matches to our movies list.
                array_push($movies, [
                    'id' => $matches[1][$x],
                    'title' => $matches[2][$x]
                ]);
            }
        }

        return $movies;
    }
}
