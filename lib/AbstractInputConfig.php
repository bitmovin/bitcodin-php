<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 23.06.15
 * Time: 09:43
 */

namespace bitcodin;

/**
 * Class AbstractInputConfig
 * @package bitcodin
 */
abstract class AbstractInputConfig
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var boolean
     */
    public $skipAnalysis = false;

    /**
     * @return mixed
     */
    public abstract function toRequestJson();
}