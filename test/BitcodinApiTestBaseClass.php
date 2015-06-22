<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../vendor/autoload.php';

class BitcodinApiTestBaseClass extends PHPUnit_Framework_TestCase {

    public function getApiKey()
    {
        return json_decode(file_get_contents(__DIR__.'/config.json'))->apiKey;
    }
}
