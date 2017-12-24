<?php

namespace xvilo\Movieskoop;

use JsonSerializable;

class ShowPricing implements JsonSerializable
{
    /** @var int */
    private $forShowId = 0;

    /** @var array */
    private $pricingTiers = [];

    /** @var array */
    private $pricing = [];

    /**
     * ShowPricing constructor.
     * This class expects a showId to get it's pricing for.
     * And the pageBody html. This class will extract all the needed data from it.
     *
     * @param int $showId
     * @param string $pageBody
     */
    public function __construct(int $showId, string $pageBody)
    {
        // Set forShowId
        $this->setForShowId($showId);

        // Start populating class and data extraction.
        $this->populateobject($pageBody);
    }

    private function populateObject(string $pageBody)
    {
        // Get pricing tiers
        $pricingTiers = $this->getPricingTiersFromPageBody($pageBody);
        $this->setPricingTiers($pricingTiers);

        // Get pricing per tier
        $pricing = $this->getPricingPerTier($pricingTiers, $pageBody);
        $this->setPricing($pricing);
    }

    /**
     * @param string $pageBody
     * @throws \Exception;
     * @return array
     */
    private function getPricingTiersFromPageBody(string $pageBody) : array
    {
        // Get all possible tiers
        preg_match('/class="rankselect" name="rankid\[' . $this->getForShowId() . '\]">(.*?)<\/select>/', $pageBody, $matches);

        if (!isset($matches[1])) {
            throw new \Exception("Couldn't get tiers for");
        }

        // Match every tier
        preg_match_all('/value="([0-9]{1,})">(.*?)<\/option>/', $matches[1], $matches, PREG_SET_ORDER);

        $pricingTiers = [];

        foreach ($matches as $match) {
            $pricingTiers[$match[1]] = $match[2];
        }

        return $pricingTiers;
    }

    /**
     * @param array $pricingTiers
     * @param string $pageBody
     * @return array
     */
    private function getPricingPerTier(array $pricingTiers, string $pageBody) : array
    {
        $prices = [];

        foreach ($pricingTiers as $tierId => $tierName) {
            $price = $this->getTierPricing($tierId, $this->getForShowId(), $pageBody);
            $prices[$tierId] = $price;
        }

        return $prices;
    }

    /**
     * @param int $tierId
     * @param int $showId
     * @param string $pageBody
     * @throws \Exception;
     * @return float
     */
    private function getTierPricing(int $tierId, int $showId, string $pageBody) : float
    {
        // Get pricing html for tier and show from pageBody html
        preg_match('/<div class="showslist show_' . $showId . '">(.*?)<div class="clearfix"><\/div><\/div><\/div>/s', $pageBody, $matches);

        if (!isset($matches[1])) {
            throw new \Exception("Couldn't get tier pricing; tier {$tierId}, show {$showId}");
        }

        // Match price for tier from pricing data
        preg_match('/class="ticketrow rank_' . $tierId . '">.*? &euro; ([0-9]{1,},[0-9]{1,2})/s', $matches[1], $matches);

        return floatval(str_replace(',', '.', $matches[1]));
    }

    /**
     * @param int $showId
     */
    private function setForShowId(int $showId)
    {
        $this->forShowId = $showId;
    }

    /**
     * @return int
     */
    public function getForShowId() : int
    {
        return $this->forShowId;
    }

    /**
     * @param array $pricingTiers
     */
    private function setPricingTiers(array $pricingTiers)
    {
        $this->pricingTiers = $pricingTiers;
    }

    /**
     * @return array
     */
    public function getPricingTiers() : array
    {
        return $this->pricingTiers;
    }

    /**
     * @param array $pricing
     */
    private function setPricing(array $pricing)
    {
        $this->pricing = $pricing;
    }

    /**
     * @return array
     */
    public function getPricing() : array
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
            'pricing' => $this->getForShowId(),
            'forShowId' => $this->getPricingTiers(),
            'getPricingPerTier' => $this->getPricing(),
        ];
    }
}
