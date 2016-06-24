<?php

namespace bitcodin;

/**
 * Class GCSInputConfig
 * @package bitcodin
 */
class GCSInputConfig extends AbstractInputConfig
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
    public $bucket;

    /**
     * @var string
     */
    public $objectKey;

    public function __construct()
    {
        $this->type = InputType::GCS;
    }

    /**
     * @return string
     */
    public function toRequestJson()
    {
        $inputObj = array();
        $inputObj['type'] = $this->type;
        $inputObj['skipAnalysis'] = $this->skipAnalysis;
        $inputObj['accessKey'] = $this->accessKey;
        $inputObj['secretKey'] = $this->secretKey;
        $inputObj['bucket'] = $this->bucket;
        $inputObj['objectKey'] = $this->objectKey;

        return json_encode($inputObj);
    }
}
