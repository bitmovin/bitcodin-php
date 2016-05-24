<?php

    namespace test\input;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\Bitcodin;
    use bitcodin\exceptions\BitcodinResourceNotFoundException;
    use bitcodin\Input;
    use test\BitcodinApiTestBaseClass;

    abstract class AbstractInputTest extends BitcodinApiTestBaseClass
    {

        public function __construct()
        {
            parent::__construct();

            Bitcodin::setApiToken($this->getApiKey());
        }

        protected function getInput(Input $input)
        {
            $input = Input::get($input->inputId);
            $this->checkInput($input);

            return $input;
        }

        protected function deleteInput(Input $input)
        {
            $this->setExpectedException(BitcodinResourceNotFoundException::class);

            $input->delete();
            $this->getInput($input);
        }

        protected function listInput()
        {
            $this->markTestIncomplete("incomplete");

            foreach (Input::getListAll() as $output) {
                $this->checkInput($output);
            }
        }

        protected function checkInput(Input $input)
        {
            $this->assertInstanceOf(Input::class, $input);
            $this->assertNotNull($input->inputId);
            $this->assertTrue(is_numeric($input->inputId), 'inputId');
            $this->assertTrue(is_string($input->filename), 'filename');
            $this->assertTrue(is_string($input->thumbnailUrl), 'thumbnailUrl');
            $this->assertTrue(is_string($input->inputType), 'inputType ');
            $this->assertTrue(is_array($input->mediaConfigurations), 'mediaConfigurations');
        }
    }
