<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;


use bitcodin\exceptions\BitcodinException;

class EncodingProfile extends ApiResource {

    const URL_CREATE = '/encoding-profile/create';


    public static function create($name, $videoStreamConfigs, AudioStreamConfig $audioStreamConfigs)
    {
        $postData = array('name' => $name, 'videoStreamConfigs' => array(), 'audioStreamConfigs' => array());
        foreach($videoStreamConfigs as $config )
        {
            /** @var VideoStreamConfig $config */
            $postData['videoStreamConfigs'][] = $config->getConfig();
        }


        $postData['audioStreamConfigs'][] = $audioStreamConfigs->getConfig();


       $response = self::_postRequest(self::URL_CREATE, $postData);


        self::_checkExpectedStatus($response, 200); //Todo change to 201
        return json_decode($response->getBody()->getContents());
    }

}