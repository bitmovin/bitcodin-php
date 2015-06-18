<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:20
 */

namespace bitcodin;

use bitcodin\exceptions\BitcodinException;

class Bitcodin {

    static private $apiKey = null;

    const BASE_URL = 'http://portal.bitcodin.com/api';
    const API_KEY_FIELD_NAME = 'bitcodin-api-key';

    public static function setApiToken($token)
    {
        self::$apiKey = $token;
    }

    public static function getApiToken()
    {
        if (self::$apiKey === null)
            throw new BitcodinException('Api token is not set!');

        return self::$apiKey;
    }


}