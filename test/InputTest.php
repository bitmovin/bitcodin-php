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
use bitcodin\Input;
use bitcodin\FtpInput;




class InputTest extends BitcodinApiTestBaseClass {

    const FTP_FILE = '/Homepage_Summer_v10.webm';
    const URL_FILE = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';

    public function testCreateUrlInput()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $input = UrlInput::create(array('url' => self::URL_FILE));
        $this->assertInstanceOf('bitcodin\UrlInput', $input);
        $this->assertNotNull($input->inputId);
    }

    public function testCreateFtpInput()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $url = $this->getKey('ftpServer').self::FTP_FILE;
        $password = $this->getKey('ftpPassword');
        $user = $this->getKey('ftpUser');
        $input = FtpInput::create(array('url' => $url, 'username'=> $user, 'password'=> $password));
        $this->assertInstanceOf('bitcodin\FtpInput', $input);
        $this->assertNotNull($input->inputId);
    }

    /**
     * @depends InputTest::testCreateUrlInput
     */
    public function testGetInput()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $input = UrlInput::create(array('url' => self::URL_FILE));
        $inputGot = UrlInput::get($input->inputId);
        $this->assertInstanceOf('bitcodin\Input', $inputGot);
        $this->assertEquals($input->inputId, $inputGot->inputId);
    }

    /**
     * @depends InputTest::testGetInput
     */
    public function testDeleteInput()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $input = UrlInput::create(array('url' => self::URL_FILE));
        $input->delete();

        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        UrlInput::get($input->inputId);
    }


    /**
     * @depends InputTest::testCreateUrlInput
     */
    public function testAnalyzeInput()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $input = UrlInput::create(array('url' => self::URL_FILE));
        $input->analyze();
        $this->assertInstanceOf('bitcodin\UrlInput', $input);
        $this->assertNotNull($input->inputId);
    }

    public function tearDown()
    {
        /* DELETE ALL INPUTS */
        Input::deleteAll();
    }


}
