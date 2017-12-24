<?php

namespace xvilo\Movieskoop;

class Movie
{
    /** @var string */
    private $id = '';

    /** @var string  */
    private $movieUrl = '';

    /**
     * Movie constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->setId($id);
        $this->setMovieUrl('http://movieskoop.nl' . $this->getId());
        $this->populateObject();
    }

    private function populateObject()
    {
        $pageBodyStream   = Util::getPageBodyFromUrl($this->getMovieUrl());
        $pageBodyContents = $pageBodyStream->getContents();

        die(var_dump($pageBodyContents));
    }

    /**
     * @param string $id
     */
    private function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    private function getId()
    {
        return $this->id;
    }

    /**
     * @param string $movieUrl
     */
    private function setMovieUrl(string $movieUrl)
    {
        $this->movieUrl = $movieUrl;
    }

    /**
     * @return string
     */
    private function getMovieUrl()
    {
        return $this->movieUrl;
    }
}