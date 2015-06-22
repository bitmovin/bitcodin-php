<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/BitcodinApiTestBaseClass.php';

use bitcodin\Bitcodin;
use bitcodin\UrlInput;




class InputTest extends BitcodinApiTestBaseClass {

    public function testCreateUrlInput()
    {

        Bitcodin::setApiToken($this->getApiKey()); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

        /* CREATE URL INPUT */
        $input = UrlInput::create(array('url' => 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv'));

    }


    public function testInvalidDomains()
    {


    }


}
