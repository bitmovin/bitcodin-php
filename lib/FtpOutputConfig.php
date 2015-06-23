<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 23.06.15
 * Time: 12:57
 */

namespace bitcodin;


class FtpOutputConfig extends AbstractOutputConfig
{

    public $username;
    public $password;

    public $passive = true;

    public function __construct()
    {
        $this->type = 'ftp';
    }
}