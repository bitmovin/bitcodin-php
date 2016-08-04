<?php

namespace bitcodin;

/**
 * Class ClearKeyEncryptionConfig
 * @package bitcodin
 */
class ClearKeyEncryptionConfig extends AbstractDRMConfig
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $kid;

    public function __construct()
    {
        $this->system = DRMTypes::CLEARKEY;
    }

    /**
     * @return string
     */
    public function getRequestBody()
    {
        $array = [];
        $array['system'] = $this->system;
        $array['key'] = $this->key;
        $array['kid'] = $this->kid;
        $array['method'] = $this->method;

        return json_encode($array);
    }
}
