<?php

    namespace test\output;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\FtpOutputConfig;
    use bitcodin\Output;

    class FtpOutputTest extends AbstractOutputTest
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
            $outputConfig = new FtpOutputConfig();
            $outputConfig->name = "TestFtpOutput";
            $outputConfig->host = str_replace('ftp://', '', $this->getKey('ftpServer')) . '/content';
            $outputConfig->username = $this->getKey('ftpUser');
            $outputConfig->password = $this->getKey('ftpPassword');

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
