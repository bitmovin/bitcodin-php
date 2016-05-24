<?php

    namespace test\input;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\Input;
    use bitcodin\S3InputConfig;

    class S3InputTest extends AbstractInputTest
    {

        const S3_FILE = 'Sintel-original-short.mkv';

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
