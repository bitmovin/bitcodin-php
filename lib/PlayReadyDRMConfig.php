<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 28.07.15
 * Time: 10:15
 */

namespace bitcodin;

/**
 * Class PlayReadyDRMConfig
 * @package bitcodin
 */
class PlayReadyDRMConfig extends AbstractDRMConfig
{
    /**
     * @var string
     */
    public $key = null;

    /**
     * @var string
     */
    public $keySeed = null;

    /**
     * @var string
     */
    public $kid;

    /**
     * @var string
     */
    public $laUrl = null;

    /**
     * @var string
     */
    public $luiUrl = null;

    /**
     * @var string
     */
    public $dsId = null;

    /**
     * @var string
     */
    public $customAttributes = null;

    public function __construct()
    {
        $this->system = DRMTypes::PLAYREADY;
    }

    /**
     * @return string
     */
    public function getRequestBody()
    {
        $array = [];
        $array['system'] = $this->system;

        if (!is_null($this->key) && $this->key !== '')
            $array['key'] = $this->key;

        if (!is_null($this->keySeed) && $this->keySeed !== '')
            $array['keySeed'] = $this->keySeed;

        $array['kid'] = $this->kid;

        if (!is_null($this->laUrl) && $this->laUrl !== '')
            $array['laUrl'] = $this->laUrl;

        if (!is_null($this->luiUrl) && $this->luiUrl !== '')
            $array['luiUrl'] = $this->luiUrl;

        if (!is_null($this->dsId) && $this->dsId !== '')
            $array['dsId'] = $this->dsId;

        if (!is_null($this->customAttributes) && $this->customAttributes !== '')
            $array['customAttributes'] = $this->customAttributes;

        $array['method'] = $this->method;

        return json_encode($array);
    }
}