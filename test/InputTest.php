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
use bitcodin\Input;
use bitcodin\HttpInputConfig;
use bitcodin\FtpInputConfig;



class InputTest extends BitcodinApiTestBaseClass {

    const FTP_FILE = '/input_test/Homepage_Summer_v10.webm';
    const URL_FILE = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';


    public function testCreateUrlInput()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);
        $this->checkInput($input);
        return $input;
    }



    public function testCreateFtpInput()
    {
        $inputConfig = new FtpInputConfig();
        $inputConfig->url = $this->getKey('ftpServer').self::FTP_FILE;
        $inputConfig->username = $this->getKey('ftpUser');
        $inputConfig->password =  $this->getKey('ftpPassword');

        $input = Input::create($inputConfig);
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends InputTest::testCreateFtpInput
     */
    public function testUpdateInput(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends InputTest::testCreateUrlInput
     */
    public function testGetInput(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends InputTest::testGetInput
     */
    public function testDeleteInput(Input $input)
    {
        $input->delete();
        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        Input::get($input->inputId);
    }


    /**
     * @depends InputTest::testCreateUrlInput
     */
    public function testAnalyzeInput()
    {
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);
        $input->analyze();
        $this->checkInput($input);
    }

    public function testGetList()
    {
        foreach(Input::getListAll() as $input)
        {
            $this->checkInput($input);
        }
    }

    public function testDeleteAll()
    {
        $count = 10;

        for($num = 0; $num < $count; $num++)
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;
            $input = Input::create($inputConfig);
        }

        Input::deleteAll();

        $this->assertEquals(0, sizeof(Input::getListAll()));
    }


    static public function tearDownAfterClass() {
        Input::deleteAll();
    }

    private function checkInput(Input $input)
    {
        $this->assertInstanceOf('bitcodin\Input', $input);
        $this->assertNotNull($input->inputId);
        $this->assertTrue(is_numeric($input->inputId), 'inputId');
        $this->assertTrue(is_string($input->filename), 'filename');
        $this->assertTrue(is_string($input->thumbnailUrl), 'thumbnailUrl');
        $this->assertTrue(is_string($input->inputType), 'inputType ');
        $this->assertTrue(is_array($input->mediaConfigurations), 'mediaConfigurations');

    }

}
