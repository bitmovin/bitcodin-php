<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;

/**
 * Class FtpInput
 * @package bitcodin
 */
class FtpInput extends Input
{

    const TYPE = 'url';

    /**
     * @param \stdClass $class
     */
    public function __construct(\stdClass $class)
    {
        parent::__construct($class);
    }

    /**
     * @param array $config
     * @return FtpInput
     */
    public static function create($config = array())
    {
        $response = self::_postRequest(self::URL_CREATE, $config, 201);
        return new self(json_decode($response->getBody()->getContents()));
    }
}