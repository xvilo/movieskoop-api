<?php

namespace xvilo\Movieskoop;

use GuzzleHttp\Client as Guzzle;

class Util
{
    /** @var Guzzle */
    private static $guzzleClient;

    /**
     * @param $url
     * @return \Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    public static function getPageBodyFromUrl(string $url)
    {

        $client = self::getGuzzleClient();
        $res = $client->request('GET', $url);

        if($res->getStatusCode()) {
            return $res->getBody();
        } else {
            die('OOPS! ' . $res->getStatusCode());
        }
    }

    /**
     * @return Guzzle
     */
    public static function getGuzzleClient() : Guzzle
    {
        if(self::$guzzleClient === null) {
            self::$guzzleClient = new Guzzle();
        }

        return self::$guzzleClient;
    }
}
