<?php

namespace xvilo\Movieskoop;

class Movie
{
    /** @var string */
    private $id = '';

    /**
     * Movie constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->populateObject();
    }

    private function populateObject()
    {
        $pageBodyStream   = Util::getPageBodyFromUrl('http://movieskoop.nl' . $this->id);
        $pageBodyContents = $pageBodyStream->getContents();

        die(var_dump($pageBodyContents));
    }
}