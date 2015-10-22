<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 22.10.15
 * Time: 15:33
 */

namespace bitcodin;


class AsperaInputConfig extends AbstractInputConfig
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $minBandwidth;

    /**
     * @var string
     */
    public $maxBandwidth;


    public function __construct()
    {
        $this->type = InputType::ASPERA;
    }


    public function toRequestJson()
    {
        $configObj = array();

        $configObj['type'] = $this->type;
        $configObj['url'] = $this->url;

        if (!empty($this->minBandwidth))
            $configObj['minBandwidth'] = $this->minBandwidth;

        if (!empty($this->maxBandwidth))
            $configObj['maxBandwidth'] = $this->maxBandwidth;

        return json_encode($configObj);
    }
}