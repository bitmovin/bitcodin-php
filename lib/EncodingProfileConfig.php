<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 23.06.15
 * Time: 09:18
 */

namespace bitcodin;


class EncodingProfileConfig
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $audioStreamConfigs = array();

    /**
     * @var array
     */
    public $videoStreamConfigs = array();
}