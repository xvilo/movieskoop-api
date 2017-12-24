<?php

namespace xvilo\Movieskoop;

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
        $this->setTitleFromBodyContents($pageBodyContents);

        // Extract Description
        $this->setDescriptionFromBodyContents($pageBodyContents);

        // Set Additional Data
        $this->setAdditionalDataFromBodyContents($pageBodyContents);

        // Parse and set shows for movie
        $this->parseAndSetShowsFormBodyContents($pageBodyContents);
    }

    /**
     * Sets movie title from Movieskoop webpage html data
     *
     * @param string $pageBody
     */
    private function setTitleFromBodyContents(string $pageBody)
    {
        preg_match('/<h2>([^<]+)/', $pageBody, $matches);
        $this->setTitle($matches[1]);
    }

    /**
     * Sets movie description from Movieskoop webpage html data
     * @param string $pageBody
     */
    private function setDescriptionFromBodyContents(string $pageBody)
    {
        preg_match('/Omschrijving:<\/strong><br \/>([^<]+)/s', $pageBody, $matches);
        $this->setDescription($matches[1]);
    }

    /**
     * Sets movie additionalData from Movieskoop webpage html data
     * @param string $pageBody
     */
    private function setAdditionalDataFromBodyContents(string $pageBody)
    {
        preg_match('/class="additionalinfo">(.*?<\/table>)/s', $pageBody, $matches);
        $this->setAdditionalData($matches[1]);
    }

    /**
     * parses and sets shows for movie from Movieskoop webpage html data
     * @param string $pageBody
     */
    private function parseAndSetShowsFormBodyContents(string $pageBody)
    {
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
}
