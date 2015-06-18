<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:23
 */

namespace bitcodin\exceptions;


class BitcodinException extends \Exception {

    public function __construct($msg, $code = 0)
    {
        parent::__construct($msg, $code);
    }

}