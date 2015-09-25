<?php

namespace bitcodin;


class GcsOutputConfig extends AbstractOutputConfig
{
    public $accessKey;
    public $secretKey;
    public $bucket;
    public $prefix = '';
    public $makePublic = true;

    public function __construct()
    {
        $this->type = 'gcs';
    }
}