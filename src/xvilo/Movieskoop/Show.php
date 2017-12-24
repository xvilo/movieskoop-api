<?php

namespace xvilo\Movieskoop;

use JsonSerializable;

class Show implements JsonSerializable
{
    /** @var int */
    private $id = 0;

    /** @var int */
    private $date = 0;

    /** @var int */
    private $room = '';

    /** @var array */
    private $pricing;

    /** @var array */
    private $_debug_rawInput = [];

    /**
     * Show constructor.
     * @param int $id
     * @param string $date
     * @param string $room
     * @throws \Exception
     */
    public function __construct(int $id, string $date, string $room)
    {
        $this->_debug_rawInput = [$id, $date, $room];
        if ($id === '' or $date === '' or $room === '') {
            throw new \Exception('Oops. You need to fill in all of our parameters. None of them maybe empty');
        }

        // Parse all values that are 'off'
        $parsedRoom = $this->parseRoom($room);
        $parsedDate = $this->parseDate($date);

        // Set all values
        $this->setId($id);
        $this->setDate($parsedDate);
        $this->setRoom($parsedRoom);
    }

    /**
     * @param string $room
     * @return int
     */
    private function parseRoom(string $room) : int
    {
        preg_match('/[0-9]{1,}$/s', $room, $matches);
        return (int)$matches[0];
    }

    /**
     * @param string $date
     * @return int
     */
    private function parseDate(string $date) : int
    {
        preg_match('/([0-9]{1,2}) ([a-z]{3}) - ([0-9]{2}:[0-9]{2})/s', $date, $matches);

        $fullEnglishMonth = Util::convertDutchShortMonthToFullEnglishMonth($matches[2]);
        $composedDateTime = "{$fullEnglishMonth} {$matches[1]} {$matches[3]}";

        return strtotime($composedDateTime);
    }

    /**
     * @param int $id
     */
    private function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param int $date
     */
    private function setDate(int $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime() : \DateTime
    {
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($this->getDateTimeUnixEpoch());
        return $dateTime;
    }

    /**
     * @return int
     */
    public function getDateTimeUnixEpoch() : int
    {
        return $this->date;
    }

    /**
     * @param int $room
     */
    private function setRoom(int $room)
    {
        $this->room = $room;
    }

    /**
     * @return int
     */
    public function getRoom() : int
    {
        return $this->room;
    }

    /**
     * @param ShowPricing $pricing
     */
    public function setPricing(ShowPricing $pricing)
    {
        $this->pricing = $pricing;
    }

    /**
     * @return ShowPricing
     */
    public function getPricing() : ShowPricing
    {
        return $this->pricing;
    }

    /**
     * Ability to JSONify this class.
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            'id' => $this->getId(),
            'dateTime' => $this->getDateTime(),
            'dateTimeUnixEpoch' => $this->getDateTimeUnixEpoch(),
            'pricing' => $this->getPricing(),
            'room' => $this->getRoom(),
        ];
    }
}
