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
     * @var int
     */
    public $encodingProfileId;

    /**
     * @var array
     */
    public $videoStreamConfigs = array();

    /**
     * @var array
     */
    public $audioStreamConfigs = array();

    /**
     * @param \stdClass $class
     */
    public function __construct(\stdClass $class)
    {
        parent::__construct($class);
    }

    /**
     * @param EncodingProfileConfig $encodingProfileConfig
     * @return EncodingProfile
     */
    public static function create(EncodingProfileConfig $encodingProfileConfig)
    {
        $response = self::_postRequest(self::URL_CREATE, json_encode($encodingProfileConfig), 200); //Todo change to 201
        return new self(json_decode($response->getBody()->getContents()));
    }

    /**
     * @param $id
     * @return EncodingProfile
     */
    public static function get($id)
    {
        if ($id instanceof \stdClass)
            $id = $id->encodingProfileId;

        $response = self::_getRequest(str_replace('{id}', $id, self::URL_GET), 200);

        return new self(json_decode($response->getBody()->getContents()));
    }

    /**
     * @param int $page
     * @return mixed
     */
    public static function getList($page = 1)
    {
        $response = self::_getRequest(str_replace('{page}', $page, self::URL_GET_LIST), 200);
        $responseDecode = json_decode($response->getBody()->getContents());
        $count = 0;
        foreach ($responseDecode->profiles as $profile)
            $responseDecode->profiles[$count++] = new self($profile);

        return $responseDecode;
    }


    /**
     * @return array(EncodingProfile)
     */
    public static function getListAll()
    {
        $encProfilesTotal = 1;
        $encodingProfiles = [];
        for ($page = 1; sizeof($encodingProfiles) < $encProfilesTotal; $page++) {
            $encProfileResponse = self::getList($page);
            $encProfileList = $encProfileResponse->profiles;
            $encProfilesTotal = $encProfileResponse->totalCount;

            foreach ($encProfileList as $encP) {
                $encodingProfiles[] = $encP;
            }

        }

        return $encodingProfiles;
    }
}