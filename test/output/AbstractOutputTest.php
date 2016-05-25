<?php

    namespace test\output;

    require_once __DIR__ . '/../../vendor/autoload.php';


    use bitcodin\Bitcodin;
    use bitcodin\exceptions\BitcodinResourceNotFoundException;
    use bitcodin\S3OutputConfig;
    use bitcodin\Output;
    use bitcodin\FtpOutputConfig;
    use test\BitcodinApiTestBaseClass;


    abstract class AbstractOutputTest extends BitcodinApiTestBaseClass
    {

        public function __construct()
        {
            parent::__construct();

            Bitcodin::setApiToken($this->getApiKey());
        }

        protected function getOutput(Output $output)
        {
            $output = Output::get($output->outputId);
            $this->checkOutput($output);

            return $output;
        }

        protected function deleteOutput(Output $output)
        {
            $output->delete();
            $this->setExpectedException(BitcodinResourceNotFoundException::class);
            $this->getOutput($output);
        }

        protected function listOutput()
        {
            $this->markTestIncomplete("incomplete");

            foreach (Output::getListAll() as $output) {
                $this->checkOutput($output);
            }
        }

        protected function checkOutput(Output $output)
        {
            $this->assertInstanceOf(Output::class, $output);
            $this->assertNotNull($output->outputId);
            $this->assertTrue(is_numeric($output->outputId), 'outputId is not set');
            $this->assertTrue(is_string($output->name), 'name is not set');
            $this->assertTrue(is_string($output->type), 'type is not set');
        }
    }
