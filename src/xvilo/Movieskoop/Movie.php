<?php

namespace xvilo\Movieskoop;

use xvilo\Movieskoop\Show as Show;

class Movie
{
    /** @var string */
    private $id = '';

    /** @var string */
    private $movieUrl = '';

    /** @var string */
    private $title = '';

    /** @var string */
    private $description = '';

    /** @var string */
    private $additionalData = '';

    /** @var array  */
    private $shows = [];

    /**
     * Movie constructor.
     * @param string $id the relative url to the movie page
     */
    public function __construct(string $id)
    {
        $this->setId($id);
        $this->setMovieUrl('http://movieskoop.nl' . $this->getId());
        $this->populateObject();
    }

    /**
     * Populates object with all data for
     * movie from Movieskoop website
     */
    private function populateObject()
    {
        $pageBodyStream   = Util::getPageBodyFromUrl($this->getMovieUrl());
        $pageBodyContents = $pageBodyStream->getContents();
        // Extract Title
        $title = $this->getTitleFromBodyContents($pageBodyContents);
        $this->setTitle($title);

        // Extract Description
        $description = $this->getDescriptionFromBodyContents($pageBodyContents);
        $this->setDescription($description);

        // Set Additional Data
        $additionalData = $this->getAdditionalDataFromBodyContents($pageBodyContents);
        $this->setAdditionalData($additionalData);

        // Parse and set shows for movie
        $shows = $this->parseShowsFormBodyContents($pageBodyContents);
        $this->setShows($shows);
    }

    /**
     * Sets movie title from Movieskoop webpage html data
     *
     * @param string $pageBody
     */
    private function getTitleFromBodyContents(string $pageBody)
    {
        preg_match('/<h2>([^<]+)/', $pageBody, $matches);
        return $matches[1];
    }

    /**
     * Sets movie description from Movieskoop webpage html data
     * @param string $pageBody
     */
    private function getDescriptionFromBodyContents(string $pageBody)
    {
        preg_match('/Omschrijving:<\/strong><br \/>([^<]+)/s', $pageBody, $matches);
        return $matches[1];
    }

    /**
     * Sets movie additionalData from Movieskoop webpage html data
     * @param string $pageBody
     */
    private function getAdditionalDataFromBodyContents(string $pageBody)
    {
        preg_match('/class="additionalinfo">(.*?<\/table>)/s', $pageBody, $matches);
        return $matches[1];
    }

    /**
     * parses and sets shows for movie from Movieskoop webpage html data
     * @param string $pageBody
     * @return array
     */
    private function parseShowsFormBodyContents(string $pageBody) : array
    {
        preg_match_all('/value="([0-9]{1,})">([^(]+) \((.*?)\)<\/option>/s', $pageBody, $matches);

        $allShows      = [];
        $totalMatches  = count($matches[0]);
        $showIdArray   = $matches[1];
        $showDateArray = $matches[2];
        $showRoomArray = $matches[3];

        for ($x = 0; $x <= $totalMatches - 1; $x++) {
            $show = new Show((int)$showIdArray[$x], $showDateArray[$x], $showRoomArray[$x]);
            array_push($allShows, $show);
        }

        return $allShows;
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
    public function getId()
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
    public function getMovieUrl()
    {
        return $this->movieUrl;
    }

    /**
     * @param string $title
     */
    private function setTitle(string $title)
    {
        $this->title = trim($title);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     */
    private function setDescription(string $description)
    {
        $this->description = trim($description);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $additionalData
     */
    private function setAdditionalData(string $additionalData)
    {
        $this->additionalData = trim($additionalData);
    }

    /**
     * @return string
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    /**
     * @return string
     */
    public function getFullBodyHtml()
    {
        return $this->getDescription() . $this->getAdditionalData();
    }

    /**
     * @param array $shows
     */
    private function setShows(array $shows)
    {
        $this->shows = $shows;
    }

    /**
     * @return array
     */
    public function getShows() : array
    {
        return $this->shows;
    }
}
