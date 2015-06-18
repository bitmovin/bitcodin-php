<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;


class VideoStreamConfig
{
    private $config = null;

    public static function getDefaultConfig()
    {
        return array(
            "defaultStreamId" => 0,
            "profile"         => "Main",
            "preset"          => "Standard"
        );
    }

    public function __construct($config = array())
    {
        $this->config = array_merge(self::getDefaultConfig(), $config);
    }

    public function getConfig()
    {
        return $this->config;
    }
}