<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */

namespace test;

use bitcodin\Bitcodin;

require_once __DIR__ . '/../vendor/autoload.php';


class BitcodinApiTestBaseClass extends \PHPUnit_Framework_TestCase {

    protected function getApiKey()
    {
        return self::getKey('apiKey');
    }

    protected function getApiBaseUrl()
    {
        return self::getKey('apiBaseUrl');
    }

    protected function getKey($key)
    {
        $obj = json_decode(file_get_contents(__DIR__.'/config.json'));

        if(property_exists($obj, "$key")) {
            return json_decode(file_get_contents(__DIR__.'/config.json'))->{$key};
        }

        return NULL;
    }

    public function __construct() {
        parent::__construct();

        $baseUrl = $this->getApiBaseUrl();

        if(!is_null($baseUrl))
            Bitcodin::setBaseUrl($baseUrl);
    }
}
