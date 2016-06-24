<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 23.06.15
 * Time: 09:43
 */

namespace bitcodin;

/**
 * Class WatermarkConfig
 * @package bitcodin
 */
class WatermarkConfig
{
    /**
     * @var string
     */
    public $image;

    /**
     * @var integer
     */
    public $top = NULL;
    /**
     * @var integer
     */
    public $bottom = NULL;
    /**
     * @var integer
     */
    public $left = NULL;
    /**
     * @var integer
     */
    public $right = NULL;

    /**
     * WatermarkConfig constructor.
     *
     * @param int $top
     * @param int $bottom
     * @param int $left
     * @param int $right
     */
    public function __construct($top = NULL, $bottom = NULL, $left = NULL, $right = NULL)
    {
        $this->top = $top;
        $this->bottom = $bottom;
        $this->left = $left;
        $this->right = $right;
    }
}