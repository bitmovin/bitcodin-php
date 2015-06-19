<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:23
 */

namespace bitcodin\exceptions;

/**
 * Class BitcodinException
 * @package bitcodin\exceptions
 */
class BitcodinException extends \Exception
{
    /**
     * @param string $msg
     * @param int $code
     */
    public function __construct($msg, $code = 0)
    {
        parent::__construct($msg, $code);
    }

}