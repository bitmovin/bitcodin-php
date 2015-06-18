<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;


use bitcodin\exceptions\BitcodinException;

class UrlInput extends Input {

    const TYPE = 'url';

    public static function create($inputUrl)
    {
        $response = self::_postRequest(self::URL_CREATE, array('url' => $inputUrl, 'type' => self::TYPE ));
        self::_checkExpectedStatus($response, 201);
        return json_decode($response->getBody()->getContents());
    }
}