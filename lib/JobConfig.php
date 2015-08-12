<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 23.06.15
 * Time: 10:56
 */

namespace bitcodin;

/**
 * Class JobConfig
 * @package bitcodin
 */
class JobConfig
{
    /**
     * @var Input
     */
    public $input;

    /**
     * @var EncodingProfile
     */
    public $encodingProfile;


    /**
     * @var int
     */
    public $duration;

    /**
     * @var array
     */
    public $manifestTypes = array();

    /**
     * @var DRMConfig
     */
    public $drmConfig = null;

    /**
     * @var AudioMetaData[]
     */
    public $audioMetaData = array();

    /**
     * @var string
     */
    public $speed = null;

    /**
     * @return string
     */
    public function getRequestBody()
    {
        if (is_string($this->manifestTypes))
            $this->manifestTypes = array($this->manifestTypes);
        $array = [];
        $array['inputId'] = $this->input->inputId;
        $array['encodingProfileId'] = $this->encodingProfile->encodingProfileId;
        $array['manifestTypes'] = $this->manifestTypes;

        if($this->duration != null)
            $array['duration'] = $this->duration;

        if (!is_null($this->speed))
            $array['speed'] = $this->speed;

        if (!is_null($this->drmConfig))
            $array['drmConfig'] = $this->drmConfig;

        if(!empty($this->audioMetaData))
            $array['audioMetaData'] = $this->audioMetaData;



        return json_encode($array);
    }
}