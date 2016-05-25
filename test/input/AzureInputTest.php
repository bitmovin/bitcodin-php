<?php

    namespace test\input;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\AzureBlobStorageInputConfig;
    use bitcodin\Input;

    class AzureInputTest extends AbstractInputTest
    {

        const AZURE_FILE = 'https://appstagingbitmovin.blob.core.windows.net/bitcodin-ci-inputs/Sintel-original-short.mkv';

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * @test
         * @return Input
         */
        public function create()
        {
            $inputConfig = new AzureBlobStorageInputConfig();
            $inputConfig->url = self::AZURE_FILE;
            $inputConfig->accountName = $this->getKey('azure')->accountName;
            $inputConfig->accountKey = $this->getKey('azure')->accountKey;
            $inputConfig->container = $this->getKey('azure')->container;

            $input = Input::create($inputConfig);
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @expectedException bitcodin\exceptions\BitcodinException
         */
        public function createWithInvalidSettings()
        {
            $inputConfig = new AzureBlobStorageInputConfig();
            $inputConfig->url = 'www.invalidazureinput.com/invalid/input.mkv';
            $inputConfig->accountName = $this->getKey('azure')->accountName;
            $inputConfig->accountKey = $this->getKey('azure')->accountKey;
            $inputConfig->container = 'php-api-wrapper';

            Input::create($inputConfig);
        }

        /**
         * @test
         * @depends create
         *
         * @param Input $input
         *
         * @return Input
         */
        public function update(Input $input)
        {
            $input->update();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends update
         *
         * @param Input $input
         *
         * @return Input
         */
        public function get(Input $input)
        {
            return $this->getInput($input);
        }

        /**
         * @test
         * @depends get
         *
         * @param Input $input
         *
         * @return Input
         */
        public function analyze(Input $input)
        {
            $input->analyze();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends analyze
         *
         * @param Input $input
         *
         * @return void
         */
        public function delete(Input $input)
        {
            $this->deleteInput($input);
        }

    }
