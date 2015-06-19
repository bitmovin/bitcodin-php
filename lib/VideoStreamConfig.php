<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;

/**
 * Class VideoStreamConfig
 * @package bitcodin
 */
class VideoStreamConfig
{
    /**
     * @var array|null
     */
    private $config = null;

    /**
     * @return array
     */
    public static function getDefaultConfig()
    {
        return array(
            "defaultStreamId" => 0,
            "profile"         => "Main",
            "preset"          => "Standard"
        );
    }

    /**
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->config = array_merge(self::getDefaultConfig(), $config);
    }

    /**
     * @return array|null
     */
    public function getConfig()
    {
        return $this->config;
    }
}