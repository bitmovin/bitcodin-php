<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;


use bitcodin\exceptions\BitcodinException;

class Job extends ApiResource {

    const URL_CREATE = '/job/create';


    public static function create($config = array())
    {

        if(isset($config['inputId']->inputId))
            $config['inputId'] = $config['inputId']->inputId;

        if(isset($config['encodingProfileId']->encodingProfileId))
            $config['encodingProfileId']=  $config['encodingProfileId']->encodingProfileId;


        var_dump(json_encode($config));

       $response = self::_postRequest(self::URL_CREATE, $config);


        self::_checkExpectedStatus($response, 201); //Todo change to 201
        return json_decode($response->getBody()->getContents());
    }

}