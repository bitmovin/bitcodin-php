<?php

    namespace test\input;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\FtpInputConfig;
    use bitcodin\Input;

    class FtpInputTestCase extends AbstractInputTest
    {
        private static $createdInputs;

        const USER1_FTP_FILE_1 = '/content/sintel![](){}<>?*&$,-short.mkv';
        const USER1_FTP_FILE_2 = '/content/sintel&short.mkv';
        const USER1_FTP_FILE_3 = '/content/sintel-short.mkv';
        const USER2_FTP_FILE_1 = '/content/sintel-original-short.mkv';


        /**
         * @test
         * @return mixed
         */
        public function provideInput()
        {
            $data = self::$createdInputs;
            $this->assertEquals(4, count($data[0]));

            return $data[0];
        }

        /**
         * @test
         * @dataProvider configProvider
         *
         * @param FtpInputConfig $ftpInputConfig
         *
         * @return Input
         */
        public function create($ftpInputConfig)
        {
            $input = Input::create($ftpInputConfig);
            $this->checkInput($input);

            self::$createdInputs[] = array( $input );

            return $input;
        }

        /**
         * @test
         * @dataProvider inputProvider
         *
         * @param Input $input
         *
         * @return Input
         */
        public function update($input = NULL)
        {
            if (!($input instanceof Input)) {
                $this->markTestSkipped("no input available");
            }

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


        public function inputProvider()
        {
            return self::$createdInputs;
        }

        public function configProvider()
        {
            $ftpConfig1 = $this->getKey('ftpSpecialChar1');
            $ftpConfig2 = $this->getKey('ftpSpecialChar2');
            $ftpInputFiles = array( self::USER1_FTP_FILE_1, self::USER1_FTP_FILE_2, self::USER1_FTP_FILE_3 );
            $ftpInputConfigs = array();

            /*foreach ($ftpInputFiles as $ftpInputFile) {
                $inputConfig = new FtpInputConfig();
                $inputConfig->url = $ftpConfig1->server . $ftpInputFile;
                $inputConfig->username = $ftpConfig1->user;
                $inputConfig->password = $ftpConfig1->password;

                $ftpInputConfigs[$ftpInputFile] = array( $inputConfig );
            }*/

            $ftpInputFile = self::USER2_FTP_FILE_1;
            $inputConfig = new FtpInputConfig();
            $inputConfig->url = $ftpConfig2->server . $ftpInputFile;
            $inputConfig->username = $ftpConfig2->user;
            $inputConfig->password = $ftpConfig2->password;

            $ftpInputConfigs[$ftpInputFile] = array( $inputConfig );

            return $ftpInputConfigs;
        }

        /*public function CreateFtpInput_File1()
        {
            $ftpConfig1 = $this->getKey('ftpSpecialChar1');

            $inputConfig = new FtpInputConfig();
            $inputConfig->url = $ftpConfig1->server . self::USER1_FTP_FILE_1;
            $inputConfig->username = $ftpConfig1->user;
            $inputConfig->password = $ftpConfig1->password;

            $input = Input::create($inputConfig);
            $this->checkInput($input);

            return $input;
        }

        public function CreateFtpInput_File2()
        {
            $ftpConfig1 = $this->getKey('ftpSpecialChar1');

            $inputConfig = new FtpInputConfig();
            $inputConfig->url = $ftpConfig1->server . self::USER1_FTP_FILE_2;
            $inputConfig->username = $ftpConfig1->user;
            $inputConfig->password = $ftpConfig1->password;

            $input = Input::create($inputConfig);
            $this->checkInput($input);
            return $input;
        }

        public function CreateFtpInput_File3()
        {
            $ftpConfig1 = $this->getKey('ftpSpecialChar1');

            $inputConfig = new FtpInputConfig();
            $inputConfig->url = $ftpConfig1->server . self::USER1_FTP_FILE_3;
            $inputConfig->username = $ftpConfig1->user;
            $inputConfig->password = $ftpConfig1->password;

            $input = Input::create($inputConfig);
            $this->checkInput($input);
            return $input;
        }

        public function CreateFtpInput_File4()
        {
            $ftpConfig2 = $this->getKey('ftpSpecialChar2');

            $inputConfig = new FtpInputConfig();
            $inputConfig->url = $ftpConfig2->server . self::USER2_FTP_FILE_1;
            $inputConfig->username = $ftpConfig2->user;
            $inputConfig->password = $ftpConfig2->password;

            $input = Input::create($inputConfig);
            $this->checkInput($input);
            return $input;
        }*/
    }
