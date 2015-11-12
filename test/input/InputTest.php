<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */

namespace test\input;

require_once __DIR__ . '/../../vendor/autoload.php';

use bitcodin\Bitcodin;
use bitcodin\Input;
use bitcodin\HttpInputConfig;
use bitcodin\FtpInputConfig;
use bitcodin\S3InputConfig;
use test\BitcodinApiTestBaseClass;

class InputTest extends BitcodinApiTestBaseClass {

    const FTP_FILE      = '/input_test/Homepage_Summer_v10.webm';
    const URL_FILE      = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';
    const S3_FILE       = 'Sintel-original-short.mkv';

    public function __construct() {
        parent::__construct();

        Bitcodin::setApiToken($this->getApiKey());
    }

    public function testCreateUrlInput()
    {
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

    public function testCreateS3Input()
    {
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
     * @depends testCreateUrlInput
     */
    public function testUpdateUrlInput(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends testCreateFtpInput
     */
    public function testUpdateFtpInput(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends testCreateS3Input
     */
    public function testUpdateS3Input(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends testUpdateUrlInput
     */
    public function testGetUrlInput(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends testUpdateFtpInput
     */
    public function testGetFtpInput(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends testUpdateS3Input
     */
    public function testGetS3Input(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends testGetUrlInput
     */
    public function testAnalyzeUrlInput(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends testGetFtpInput
     */
    public function testAnalyzeFtpInput(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends testGetS3Input
     */
    public function testAnalyzeS3Input(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends testAnalyzeUrlInput
     */
    public function testDeleteUrlInput(Input $input)
    {
        $input->delete();
        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        Input::get($input->inputId);
    }

    /**
     * @depends testAnalyzeFtpInput
     */
    public function testDeleteFtpInput(Input $input)
    {
        $input->delete();
        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        Input::get($input->inputId);
    }

    /**
     * @depends testAnalyzeS3Input
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
