<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;

/**
 * Class EncodingProfile
 * @package bitcodin
 */
class EncodingProfile extends ApiResource
{

    const URL_CREATE = '/encoding-profile/create';
    const URL_GET = '/encoding-profile/{id}';
    const URL_GET_LIST = '/encoding-profiles/{page}';

    /**
     * @param $name
     * @param $videoStreamConfigs
     * @param $audioStreamConfigs
     * @return mixed
     */
    public static function create($name, $videoStreamConfigs, $audioStreamConfigs)
    {
        $postData = array('name' => $name, 'videoStreamConfigs' => array(), 'audioStreamConfigs' => array());
        foreach ($videoStreamConfigs as $config) {
            /** @var VideoStreamConfig $config */
            $postData['videoStreamConfigs'][] = $config->getConfig();
        }

        foreach ($audioStreamConfigs as $config) {
            /** @var AudioStreamConfig $config */
            $postData['audioStreamConfigs'][] = $config->getConfig();
        }

        $response = self::_postRequest(self::URL_CREATE, $postData, 200); //Todo change to 201

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function get($id)
    {
        if ($id instanceof \stdClass)
            $id = $id->encodingProfileId;

        $response = self::_getRequest(str_replace('{id}', $id, self::URL_GET), 200);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param int $page
     * @return mixed
     */
    public static function getList($page = 1)
    {
        $response = self::_getRequest(str_replace('{page}', $page, self::URL_GET_LIST), 200);
        return json_decode($response->getBody()->getContents());
    }
}