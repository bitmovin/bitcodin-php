<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 23.06.15
 * Time: 09:18
 */

namespace bitcodin;

/**
 * Class FtpInputConfig
 * @package bitcodin
 */
class FtpInputConfig extends AbstractInputConfig
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    public function __construct()
    {
        $this->type = InputType::FTP;
    }

    /**
     * @return string
     */
    public function toRequestJson()
    {
        $configObj = array();

        $configObj['type'] = $this->type;
        $configObj['url'] = $this->url;

        if (!is_null($this->username) && $this->username !== '')
            $configObj['username'] = $this->username;

        if (!is_null($this->password) && $this->password !== '')
            $configObj['password'] = $this->password;

        return json_encode($configObj);
    }
}