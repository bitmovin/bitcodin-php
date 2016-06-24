<?php

    namespace test\input;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\HttpInputConfig;
    use bitcodin\Input;

    class HttpInputTest extends AbstractInputTest
    {

        const URL_FILE = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';

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
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;

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
