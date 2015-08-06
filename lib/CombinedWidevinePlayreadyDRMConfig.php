<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 05.08.15
 * Time: 11:19
 */

namespace bitcodin;

/**
 * Class CombinedWidevinePlayreadyDRMConfig
 * @package bitcodin
 */
class CombinedWidevinePlayreadyDRMConfig extends AbstractDRMConfig
{
    /**
     * @var string
     */
    public $pssh;

    /**
     * @var string
     */
    public $key;

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
        $this->system = DRMTypes::COMBINED_WIDEVINE_PLAYREADY;
    }

    /**
     * @return string
     */
    public function getRequestBody()
    {
        $array = [];
        $array['system'] = $this->system;
        $array['pssh'] = $this->pssh;
        $array['key'] = $this->key;
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