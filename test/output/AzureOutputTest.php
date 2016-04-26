<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */

namespace test\output;

require_once __DIR__ . '/../../vendor/autoload.php';

use bitcodin\AzureOutputConfig;
use bitcodin\Bitcodin;
use bitcodin\Output;
use test\BitcodinApiTestBaseClass;

class AzureOutputTest extends BitcodinApiTestBaseClass
{

    public function __construct() {
        parent::__construct();

        Bitcodin::setApiToken($this->getApiKey());
    }

    public function testCreateAzureOutput()
    {
        $this->markTestSkipped();

        Bitcodin::setApiToken($this->getApiKey());
        $outputConfig = new AzureOutputConfig();
        $outputConfig->accountName =  $this->getKey('azure')->accountName;
        $outputConfig->accountKey = $this->getKey('azure')->accountKey;
        $outputConfig->container = $this->getKey('azure')->container;
        $outputConfig->prefix = $this->getKey('azure')->prefix;
        $outputConfig->name = 'azure test';
        $output = Output::create($outputConfig);
        $this->checkOutput($output);

        return $output;
    }

    private function checkOutput(Output $output)
    {
        $this->assertInstanceOf('bitcodin\Output', $output);
        $this->assertNotNull($output->outputId);
        $this->assertTrue(is_numeric($output->outputId), 'outputId is not set');
        $this->assertTrue(is_string($output->name), 'name is not set');
        $this->assertTrue(is_string($output->type), 'type is not set');
    }

    /**
     * @depends testCreateAzureOutput
     */
    public function testUpdateAzureOutput(Output $output)
    {
        $output->update();
        $this->checkOutput($output);

        return $output;
    }

}
