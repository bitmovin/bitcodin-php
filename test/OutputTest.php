<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/BitcodinApiTestBaseClass.php';

use bitcodin\Bitcodin;
use bitcodin\S3OutputConfig;
use bitcodin\Output;
use bitcodin\FtpOutputConfig;

class OutputTest extends BitcodinApiTestBaseClass
{

    public function testCreateS3Output()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $s3Config = $this->getKey('s3');
        $outputConfig = new S3OutputConfig();
        $outputConfig->accessKey = $s3Config->accessKey;
        $outputConfig->secretKey = $s3Config->secretKey;
        $outputConfig->name = $s3Config->name;
        $outputConfig->bucket = $s3Config->bucket;
        $outputConfig->region = $s3Config->region;
        $outputConfig->makePublic = false;

        $output = Output::create($outputConfig);
        $this->checkOutput($output);

        return $output;
    }

    public function testCreateFtpOutput()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $outputConfig = new FtpOutputConfig();
        $outputConfig->name = "TestS3Output";
        $outputConfig->host = str_replace('ftp://', '', $this->getKey('ftpServer'));
        $outputConfig->username = $this->getKey('ftpUser');
        $outputConfig->password = $this->getKey('ftpPassword');

        $output = Output::create($outputConfig);
        $this->checkOutput($output);
    }

    /**
     * @depends OutputTest::testCreateS3Output
     */
    public function testGetOutput(Output $output)
    {
        $output = Output::get($output->outputId);
        $this->checkOutput($output);

        return $output;
    }

    /**
     * @depends OutputTest::testGetOutput
     */
    public function testDeleteOutput(Output $output)
    {
        $output->delete();
        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        Output::get($output);
    }


    public function testGetList()
    {
        foreach (Output::getListAll() as $output) {
            $this->checkOutput($output);
        }
    }

    public function testDeleteAll()
    {
        $count = 10;

        for ($num = 0; $num < $count; $num++) {
            Bitcodin::setApiToken($this->getApiKey());
            $s3Config = $this->getKey('s3');
            $outputConfig = new S3OutputConfig();
            $outputConfig->accessKey = $s3Config->accessKey;
            $outputConfig->secretKey = $s3Config->secretKey;
            $outputConfig->name = $s3Config->name;
            $outputConfig->bucket = $s3Config->bucket;
            $outputConfig->region = $s3Config->region;
            $outputConfig->makePublic = false;

            Output::create($outputConfig);
        }

        Output::deleteAll();

        $this->assertEquals(0, sizeof(Output::getListAll()));
    }

    private function checkOutput(Output $output)
    {
        $this->assertInstanceOf('bitcodin\Output', $output);
        $this->assertNotNull($output->outputId);
        $this->assertTrue(is_numeric($output->outputId), 'outputId is not set');
        $this->assertTrue(is_string($output->name), 'name is not set');
        $this->assertTrue(is_string($output->type), 'type is not set');
    }

}
