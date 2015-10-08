<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 14.09.15
 * Time: 10:27
 */

namespace bitcodin;

class AzureOutputConfig extends AbstractOutputConfig
{
    public $accountName;
    public $accountKey;
    public $container;
    public $prefix;

    public $passive = true;

    public function __construct()
    {
        $this->type = 'azure';
    }
}