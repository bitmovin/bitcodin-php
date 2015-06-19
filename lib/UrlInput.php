<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;

/**
 * Class UrlInput
 * @package bitcodin
 */
class UrlInput extends Input {

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
     * @return UrlInput
     */
    public static function create($config = array())
    {
        $config['url'] = str_replace('?dl=0','?dl=1', $config['url']);
        $config['type'] = self::TYPE;

        $response = self::_postRequest(self::URL_CREATE, $config, 201);
        return new self(json_decode($response->getBody()->getContents()));
    }
}