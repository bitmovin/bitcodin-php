<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../vendor/autoload.php';

use bitcodin\Bitcodin;
use bitcodin\Input;
use bitcodin\HttpInputConfig;
use bitcodin\FtpInputConfig;
use bitcodin\S3InputConfig;
use test\BitcodinApiTestBaseClass;

class InputTest extends BitcodinApiTestBaseClass {

    const FTP_FILE      = '/content/input_test/Homepage_Summer_v10.webm';
    const URL_FILE      = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';
    const S3_FILE       = 'Sintel-original-short.mkv';

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
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new FtpInputConfig();
        $inputConfig->url = $this->getKey('ftpServer').self::FTP_FILE;
        $inputConfig->username = $this->getKey('ftpUser');
        $inputConfig->password =  $this->getKey('ftpPassword');

        $input = Input::create($inputConfig);
        $this->checkInput($input);
        return $input;
    }

    public function testCreateS3Input()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $s3Config = $this->getKey('s3input');

        $inputConfig = new S3InputConfig();
        $inputConfig->accessKey = $s3Config->accessKey;
        $inputConfig->secretKey = $s3Config->secretKey;;
        $inputConfig->region = $s3Config->region;
        $inputConfig->bucket = $s3Config->bucket;
        $inputConfig->objectKey = self::S3_FILE;

        $input = Input::create($inputConfig);
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends InputTest::testCreateUrlInput
     */
    public function testUpdateUrlInput(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends InputTest::testCreateFtpInput
     */
    public function testUpdateFtpInput(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends InputTest::testCreateS3Input
     */
    public function testUpdateS3Input(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends InputTest::testUpdateUrlInput
     */
    public function testGetUrlInput(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends InputTest::testUpdateFtpInput
     */
    public function testGetFtpInput(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends InputTest::testUpdateS3Input
     */
    public function testGetS3Input(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends InputTest::testGetUrlInput
     */
    public function testAnalyzeUrlInput(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends InputTest::testGetFtpInput
     */
    public function testAnalyzeFtpInput(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends InputTest::testGetS3Input
     */
    public function testAnalyzeS3Input(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends InputTest::testAnalyzeUrlInput
     */
    public function testDeleteUrlInput(Input $input)
    {
        $input->delete();
        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        Input::get($input->inputId);
    }

    /**
     * @depends InputTest::testAnalyzeFtpInput
     */
    public function testDeleteFtpInput(Input $input)
    {
        $input->delete();
        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        Input::get($input->inputId);
    }

    /**
     * @depends InputTest::testAnalyzeS3Input
     */
    public function testDeleteS3Input(Input $input)
    {
        $input->delete();
        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        Input::get($input->inputId);
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
