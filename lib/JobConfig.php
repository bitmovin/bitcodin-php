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
    public $encodingProfile = null;

    /**
     * @var Output
     */
    public $output  = null;

    /**
     * @var int
     */
    public $duration;

    /**
     * @var array
     */
    public $manifestTypes = array();

    /**
     * @var AbstractDRMConfig
     */
    public $drmConfig = null;

    public $hlsEncryptionConfig = null;

    /**
     * @var AudioMetaData[]
     */
    public $audioMetaData = array();

    /**
     * @var VideoMetaData[]
     */
    public $videoMetaData = array();

    /**
     * @var string
     */
    public $speed = null;

    /**
     * @var boolean
     */
    public $extractClosedCaptions = false;

    /**
     * @var string
     */
    public $location = null;

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
        $array['extractClosedCaptions'] = $this->extractClosedCaptions;

        if(!is_null($this->output))
            $array['outputId'] = $this->output->outputId;

        if($this->duration != null)
            $array['duration'] = $this->duration;

        if (!is_null($this->speed))
            $array['speed'] = $this->speed;

        if (!is_null($this->drmConfig))
            $array['drmConfig'] = $this->drmConfig;

        if(!empty($this->audioMetaData))
            $array['audioMetaData'] = $this->audioMetaData;

        if(!empty($this->videoMetaData))
            $array['videoMetaData'] = $this->videoMetaData;

        if (!is_null($this->hlsEncryptionConfig))
            $array['hlsEncryptionConfig'] = $this->hlsEncryptionConfig;

        if(!is_null($this->location))
            $array['location'] = $this->location;

        return json_encode($array);
    }
}