<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 11.08.15
 * Time: 14:06
 */

namespace bitcodin;

/**
 * Class S3InputConfig
 * @package bitcodin
 */
class S3InputConfig extends AbstractInputConfig
{
    /**
     * @var string
     */
    public $accessKey;

    /**
     * @var string
     */
    public $secretKey;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $bucket;

    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $objectKey;

    public function __construct()
    {
        $this->type = InputType::S3;
    }

    /**
     * @return string
     */
    public function toRequestJson()
    {
        $inputObj = array();
        $inputObj['type'] = $this->type;
        $configObj['skipAnalysis'] = $this->skipAnalysis;
        $inputObj['accessKey'] = $this->accessKey;
        $inputObj['secretKey'] = $this->secretKey;

        if (!is_null($this->host) && $this->host !== '')
            $inputObj['host'] = $this->host;

        $inputObj['bucket'] = $this->bucket;
        $inputObj['region'] = $this->region;
        $inputObj['objectKey'] = $this->objectKey;

        return json_encode($inputObj);
    }
}