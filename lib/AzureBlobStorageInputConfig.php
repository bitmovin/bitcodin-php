<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 23.06.15
 * Time: 09:18
 */

namespace bitcodin;

/**
 * Class AzureBlobStorageInputConfig
 * @package bitcodin
 */
class AzureBlobStorageInputConfig extends AbstractInputConfig
{
    /**
     * @var string
     */
    public $accountName;

    /**
     * @var string
     */
    public $accountKey;

    /**
     * @var string
     */
    public $container;

    /**
     * @var string
     */
    public $url;


    public function __construct()
    {
        $this->type = InputType::ABS;
    }


    public function toRequestJson()
    {
        $configObj = array();

        $configObj['accountName'] = $this->accountName;
        $configObj['accountKey'] = $this->accountKey;
        $configObj['container'] = $this->container;
        $configObj['url'] = $this->url;

        return json_encode($configObj);
    }

}