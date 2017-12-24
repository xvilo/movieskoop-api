<?php

namespace xvilo\Movieskoop;

use GuzzleHttp\Client as Guzzle;

class Util
{
    /** @var Guzzle */
    private static $guzzleClient;

    /** @var array */
    private static $months = [
        'jan' => 'January',
        'feb' => 'Februari',
        'mrt' => 'March',
        'apr' => 'April',
        'jun' => 'June',
        'jul' => 'July',
        'aug' => 'August',
        'sep' => 'September',
        'okt' => 'October',
        'nov' => 'November',
        'dec' => 'December'
    ];

    /**
     * @param $url
     * @return \Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    public static function getPageBodyFromUrl(string $url)
    {
        $client = self::getGuzzleClient();
        $res = $client->request('GET', $url);

        if ($res->getStatusCode()) {
            return $res->getBody();
        } else {
            throw new \Exception('OOPS! ' . $res->getStatusCode());
        }
    }

    /**
     * @return Guzzle
     */
    public static function getGuzzleClient() : Guzzle
    {
        if (self::$guzzleClient === null) {
            self::$guzzleClient = new Guzzle();
        }

        return self::$guzzleClient;
    }

    /**
     * @param string $month
     * @return string
     * @throws \Exception
     */
    public static function convertDutchShortMonthToFullEnglishMonth(string $month) : string
    {
        if (isset(self::$months[$month])) {
            return self::$months[$month];
        } else {
            throw new \Exception("No matching English month found for '{$month}'");
        }
    }
}
