<?php

namespace xvilo\Movieskoop;

class Movieskoop
{
    /**
     * @return array
     */
    public function getMoviesListAsArray() : array
    {
        return $this->getRawMoviesHtml();
    }

    /**
     * @return array
     */
    public function getMovies() : array
    {
        $movies = $this->getMoviesListAsArray();
        $movieObjects = [];

        foreach ($movies as $movie) {
            array_push($movieObjects, $this->getMovie($movie['id']));
        }

        return $movieObjects;
    }

    /**
     * @param string $id
     * @return Movie
     */
    public function getMovie(string $id) : Movie
    {
        return new Movie($id);
    }


    /**
     * @return array
     */
    private function getRawMoviesHtml() : array
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
