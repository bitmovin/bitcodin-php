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

    public function __construct() {
        parent::__construct();

        Bitcodin::setApiToken($this->getApiKey());
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
     * @depends testCreateFtpInput
     */
    public function testUpdateFtpInput(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
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
     * @depends testGetFtpInput
     */
    public function testAnalyzeFtpInput(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
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

}
