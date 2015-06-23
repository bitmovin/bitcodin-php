<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 23.06.15
 * Time: 12:57
 */

namespace bitcodin;


class S3OutputConfig extends AbstractOutputConfig
{
    public $host;
    public $accessKey;
    public $secretKey;
    public $bucket;
    public $prefix = '';
    public $makePublic = true;
    public $region;

    public function __construct()
    {
        $this->type = 's3';
    }
}