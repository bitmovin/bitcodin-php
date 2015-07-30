<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 28.07.15
 * Time: 10:07
 */

namespace bitcodin;

/**
 * Class AbstractInputConfig
 * @package bitcodin
 */
abstract class AbstractDRMConfig
{
    /**
     * @var string
     */
    public $system;

    /**
     * @var string
     */
    public $method;
}