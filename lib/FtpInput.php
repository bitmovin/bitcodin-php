<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;


class FtpInput extends Input {

    const TYPE = 'url';


    public static function create($config = array())
    {
        $config['isSaving'] = true;
        $response = self::_postRequest(self::URL_CREATE, $config);
        self::_checkExpectedStatus($response, 201);
        return json_decode($response->getBody()->getContents());
    }
}