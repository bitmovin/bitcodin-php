<?php

    namespace test\output;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\AzureOutputConfig;
    use bitcodin\Output;

    class AzureOutputTest extends AbstractOutputTest
    {

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * @test
         * @return Output
         */
        public function create()
        {
            $outputConfig = new AzureOutputConfig();
            $outputConfig->accountName = $this->getKey('azure')->accountName;
            $outputConfig->accountKey = $this->getKey('azure')->accountKey;
            $outputConfig->container = $this->getKey('azure')->container;
            $outputConfig->prefix = $this->getKey('azure')->prefix;
            $outputConfig->name = 'azure test';

            $output = Output::create($outputConfig);
            $this->checkOutput($output);

            return $output;
        }

        /**
         * @depends create
         *
         * @param Output $output
         *
         * @return Output
         */
        public function update(Output $output)
        {
            $output->update();
            $this->checkOutput($output);

            return $output;
        }

        /**
         * @test
         * @depends create
         *
         * @param Output $output
         *
         * @return Output
         */
        public function get(Output $output)
        {
            return $this->getOutput($output);
        }

        /**
         * @test
         * @depends get
         *
         * @param Output $output
         */
        public function delete(Output $output)
        {
            return $this->deleteOutput($output);
        }

    }
