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
     * @var int
     */
    public $bitrate;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $defaultStreamId = 0;

    /**
     * @var string
     */
    public $profile = 'Main';

    /**
     * @var string
     */
    public $preset = 'Standard';
}