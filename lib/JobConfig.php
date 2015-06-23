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
     * @var array
     */
    public $manifestTypes = array();

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

        return json_encode($array);
    }
}