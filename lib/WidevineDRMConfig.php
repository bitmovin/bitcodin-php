<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 28.07.15
 * Time: 09:23
 */

namespace bitcodin;

/**
 * Class WidevineDRMConfig
 * @package bitcodin
 */
class WidevineDRMConfig extends AbstractDRMConfig
{
    /**
     * @var string
     */
    public $provider;

    /**
     * @var string
     */
    public $signingKey;

    /**
     * @var string
     */
    public $signingIV;

    /**
     * @var string
     */
    public $requestUrl;

    /**
     * @var string
     */
    public $contentId;

    public function __construct()
    {
        $this->system = DRMTypes::WIDEVINE;
    }

    /**
     * @return string
     */
    public function getRequestBody()
    {
        $array = [];
        $array['system'] = $this->system;
        $array['provider'] = $this->provider;
        $array['signingKey'] = $this->signingKey;
        $array['signingIV'] = $this->signingIV;
        $array['requestUrl'] = $this->requestUrl;
        $array['contentId'] = $this->contentId;
        $array['method'] = $this->method;

        return json_encode($array);
    }
}