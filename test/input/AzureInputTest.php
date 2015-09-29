<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.09.15
 * Time: 13:57
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use bitcodin\AzureBlobStorageInputConfig;
use bitcodin\Bitcodin;
use bitcodin\Input;
use test\BitcodinApiTestBaseClass;

class AzureInputTest extends BitcodinApiTestBaseClass {

    const AZURE_FILE    = 'http://bitblobstorage.blob.core.windows.net/php-api-wrapper/Sintel-original-short.mkv';

    public function __construct() {
        parent::__construct();

        Bitcodin::setApiToken($this->getApiKey());
    }

    public function testCreateAzureInput()
    {

        $inputConfig = new AzureBlobStorageInputConfig();
        $inputConfig->url = self::AZURE_FILE;
        $inputConfig->accountName =  $this->getKey('azure')->accountName;
        $inputConfig->accountKey = $this->getKey('azure')->accountKey;
        $inputConfig->container = 'php-api-wrapper';

        $input = Input::create($inputConfig);
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends testCreateAzureInput
     */
    public function testUpdateAzureInput(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends testUpdateAzureInput
     */
    public function testGetAzureInput(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends testGetAzureInput
     */
    public function testAnalyzeAzureInput(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends testAnalyzeAzureInput
     */
    public function testDeleteAzureInput(Input $input)
    {
        $input->delete();
        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        Input::get($input->inputId);
    }

    public function testCreateInvalidFileAzureInput()
    {

        $inputConfig = new AzureBlobStorageInputConfig();
        $inputConfig->url = 'www.invalidazureinput.com/invalid/input.mkv';
        $inputConfig->accountName =  $this->getKey('azure')->accountName;
        $inputConfig->accountKey = $this->getKey('azure')->accountKey;
        $inputConfig->container = 'php-api-wrapper';
        $this->setExpectedException('bitcodin\exceptions\BitcodinException');
        Input::create($inputConfig);
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
