<?php


    namespace test\input;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\AudioStreamConfig;
    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use bitcodin\FtpInputConfig;
    use bitcodin\Input;
    use bitcodin\Job;
    use bitcodin\JobConfig;
    use bitcodin\ManifestTypes;
    use bitcodin\VideoStreamConfig;
    use DateTime;

    class FtpInputJobTest extends AbstractInputTest
    {

        const USER1_FTP_FILE_1 = '/content/sintel![](){}<>?*&$,-short.mkv';
        const USER1_FTP_FILE_2 = '/content/sintel&short.mkv';
        const USER1_FTP_FILE_3 = '/content/sintel-short.mkv';

        const USER2_FTP_FILE_1 = '/content/sintel-original-short.mkv';

        public function setUp()
        {
            parent::setUp();
        }

        // ==== CREATE ====

        /**
         * @test
         * @return Input
         */
        public function createFtpInput_File1()
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

        /**
         * @test
         * @return Input
         */
        public function createFtpInput_File2()
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

        /**
         * @test
         * @return Input
         */
        public function createFtpInput_File3()
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

        /**
         * @test
         * @return Input
         */
        public function createFtpInput_File4()
        {
            $ftpConfig2 = $this->getKey('ftpSpecialChar2');

            $inputConfig = new FtpInputConfig();
            $inputConfig->url = $ftpConfig2->server . self::USER2_FTP_FILE_1;
            $inputConfig->username = $ftpConfig2->user;
            $inputConfig->password = $ftpConfig2->password;

            $input = Input::create($inputConfig);
            $this->checkInput($input);

            return $input;
        }

        // ==== UPDATE ====

        /**
         * @test
         * @depends createFtpInput_File1
         */
        public function updateFtpInput_File1(Input $input)
        {
            $input->update();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends createFtpInput_File2
         */
        public function updateFtpInput_File2(Input $input)
        {
            $input->update();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends createFtpInput_File3
         */
        public function updateFtpInput_File3(Input $input)
        {
            $input->update();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends createFtpInput_File4
         */
        public function updateFtpInput_File4(Input $input)
        {
            $input->update();
            $this->checkInput($input);

            return $input;
        }

        // ==== GET ====

        /**
         * @test
         * @depends updateFtpInput_File1
         */
        public function getFtpInput_File1(Input $input)
        {
            $inputGot = Input::get($input->inputId);
            $this->checkInput($inputGot);

            return $inputGot;
        }

        /**
         * @test
         * @depends updateFtpInput_File2
         */
        public function getFtpInput_File2(Input $input)
        {
            $inputGot = Input::get($input->inputId);
            $this->checkInput($inputGot);

            return $inputGot;
        }

        /**
         * @test
         * @depends updateFtpInput_File3
         */
        public function getFtpInput_File3(Input $input)
        {
            $inputGot = Input::get($input->inputId);
            $this->checkInput($inputGot);

            return $inputGot;
        }

        /**
         * @test
         * @depends updateFtpInput_File4
         */
        public function getFtpInput_File4(Input $input)
        {
            $inputGot = Input::get($input->inputId);
            $this->checkInput($inputGot);

            return $inputGot;
        }

        // ==== ANALYZE ====

        /**
         * @test
         * @depends getFtpInput_File1
         */
        public function analyzeFtpInput_File1(Input $input)
        {
            $input->analyze();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends getFtpInput_File2
         */
        public function analyzeFtpInput_File2(Input $input)
        {
            $input->analyze();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends getFtpInput_File3
         */
        public function analyzeFtpInput_File3(Input $input)
        {
            $input->analyze();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends getFtpInput_File4
         */
        public function analyzeFtpInput_File4(Input $input)
        {
            $input->analyze();
            $this->checkInput($input);

            return $input;
        }

        // ==== ENCODING ====

        /**
         * @depends analyzeFtpInput_File1
         */
        public function testCreateFtpInputJob_File1(Input $input)
        {
            $encodingProfileConfig = AbstractEncodingProfileTest::encodingProfileProvider($this->getName());

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;

            $this->markTestIncomplete("");

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf(Job::class, $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        /**
         * @depends analyzeFtpInput_File2
         */
        public function testCreateFtpInputJob_File2(Input $input)
        {
            $encodingProfileConfig = AbstractEncodingProfileTest::encodingProfileProvider($this->getName());

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf(Job::class, $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        /**
         * @depends analyzeFtpInput_File3
         */
        public function testCreateFtpInputJob_File3(Input $input)
        {
            $encodingProfileConfig = AbstractEncodingProfileTest::encodingProfileProvider($this->getName());

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf(Job::class, $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        /**
         * @depends analyzeFtpInput_File4
         */
        public function testCreateFtpInputJob_File4(Input $input)
        {
            $encodingProfileConfig = AbstractEncodingProfileTest::encodingProfileProvider($this->getName());

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf(Job::class, $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        // ==== WAIT FOR ENCODING TO BE FINISHED ====

        /**
         * @depends testCreateFtpInputJob_File1
         */
        public function testUpdateFtpInputJob_File1(Job $job)
        {
            $this->markTestIncomplete("Escaping isn't final for such filenames");
            $this->updateJob($job);
        }

        /**
         * @depends testCreateFtpInputJob_File2
         */
        public function testUpdateFtpInputJob_File2(Job $job)
        {
            $this->updateJob($job);
        }

        /**
         * @depends testCreateFtpInputJob_File3
         */
        public function testUpdateFtpInputJob_File3(Job $job)
        {
            $this->updateJob($job);
        }

        /**
         * @depends testCreateFtpInputJob_File4
         */
        public function testUpdateFtpInputJob_File4(Job $job)
        {
            $this->updateJob($job);
        }

        private function updateJob(Job $job)
        {
            /* WAIT TIL JOB IS FINISHED */
            $this->waitTillJobGetsExpectedStatus($job, Job::STATUS_FINISHED, Job::STATUS_ERROR);
            $this->assertEquals($job->status, Job::STATUS_FINISHED);

            return $job;
        }

        private function waitTillJobGetsExpectedStatus(Job $job, $expectedStatus, $notExpectedStatus, $timeOutSeconds = 1000)
        {
            $expireTime = (new DateTime())->add(new \DateInterval('PT' . $timeOutSeconds . 'S'));
            do {
                sleep(2);
                $job->update();
                $this->assertNotEquals($job->status, $notExpectedStatus);
                $this->assertTrue($expireTime >= new DateTime(), 'Timeout during job update!');

            } while ($job->status != $expectedStatus);
        }

    }