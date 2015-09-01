<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:20
 */

namespace bitcodin;

use bitcodin\exceptions\BitcodinException;

/**
 * Class Bitcodin
 * @package bitcodin
 */
class Bitcodin
{
    const BASE_URL = 'http://localhost/bitcodin-php-api/index.php';
    const API_KEY_FIELD_NAME = 'bitcodin-api-key';

    /**
     * @var string|null
     */
    static private $apiKey = NULL;

    /**
     * @param $token
     */
    public static function setApiToken($token)
    {
        self::$apiKey = $token;
    }

    /**
     * @return null|string
     * @throws BitcodinException
     */
    public static function getApiToken()
    {
        if (self::$apiKey === NULL)
            throw new BitcodinException('Api token is not set!');

        return self::$apiKey;
    }
}