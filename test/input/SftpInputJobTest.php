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
    use bitcodin\SftpInputConfig;
    use bitcodin\VideoStreamConfig;
    use DateTime;

    class SftpInputJobTest extends AbstractInputTest
    {

        const USER1_SFTP_FILE_1 = '/content/sintel![](){}<>?*&$,-short.mkv';
        const USER1_SFTP_FILE_2 = '/content/sintel&short.mkv';
        const USER1_SFTP_FILE_3 = '/content/sintel-short.mkv';
        const USER2_SFTP_FILE_1 = '/content/sintel-original-short.mkv';

        public function setUp()
        {
            parent::setUp();
        }

        // ==== CREATE ====

        /**
         * @test
         * @return Input
         */
        public function createSftpInput_File1()
        {
            $ftpConfig1 = $this->getKey('ftpSpecialChar1');

            $inputConfig = new SftpInputConfig();
            $inputConfig->url = $ftpConfig1->server . self::USER1_SFTP_FILE_1;
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
        public function createSftpInput_File2()
        {
            $ftpConfig1 = $this->getKey('ftpSpecialChar1');

            $inputConfig = new FtpInputConfig();
            $inputConfig->url = $ftpConfig1->server . self::USER1_SFTP_FILE_2;
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
        public function createSftpInput_File3()
        {
            $ftpConfig1 = $this->getKey('ftpSpecialChar1');

            $inputConfig = new FtpInputConfig();
            $inputConfig->url = $ftpConfig1->server . self::USER1_SFTP_FILE_3;
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
        public function createSftpInput_File4()
        {
            $ftpConfig2 = $this->getKey('ftpSpecialChar2');

            $inputConfig = new FtpInputConfig();
            $inputConfig->url = $ftpConfig2->server . self::USER2_SFTP_FILE_1;
            $inputConfig->username = $ftpConfig2->user;
            $inputConfig->password = $ftpConfig2->password;

            $input = Input::create($inputConfig);
            $this->checkInput($input);

            return $input;
        }

        // ==== UPDATE ====

        /**
         * @test
         * @depends createSftpInput_File1
         */
        public function updateSftpInput_File1(Input $input)
        {
            $input->update();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends createSftpInput_File2
         */
        public function updateSftpInput_File2(Input $input)
        {
            $input->update();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends createSftpInput_File3
         */
        public function updateSftpInput_File3(Input $input)
        {
            $input->update();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends createSftpInput_File4
         */
        public function updateSftpInput_File4(Input $input)
        {
            $input->update();
            $this->checkInput($input);

            return $input;
        }

        // ==== GET ====

        /**
         * @test
         * @depends updateSftpInput_File1
         */
        public function getSftpInput_File1(Input $input)
        {
            $inputGot = Input::get($input->inputId);
            $this->checkInput($inputGot);

            return $inputGot;
        }

        /**
         * @test
         * @depends updateSftpInput_File2
         */
        public function getSftpInput_File2(Input $input)
        {
            $inputGot = Input::get($input->inputId);
            $this->checkInput($inputGot);

            return $inputGot;
        }

        /**
         * @test
         * @depends updateSftpInput_File3
         */
        public function getSftpInput_File3(Input $input)
        {
            $inputGot = Input::get($input->inputId);
            $this->checkInput($inputGot);

            return $inputGot;
        }

        /**
         * @test
         * @depends updateSftpInput_File4
         */
        public function getSftpInput_File4(Input $input)
        {
            $inputGot = Input::get($input->inputId);
            $this->checkInput($inputGot);

            return $inputGot;
        }

        // ==== ANALYZE ====

        /**
         * @test
         * @depends getSftpInput_File1
         */
        public function analyzeSftpInput_File1(Input $input)
        {
            $input->analyze();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends getSftpInput_File2
         */
        public function analyzeSftpInput_File2(Input $input)
        {
            $input->analyze();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends getSftpInput_File3
         */
        public function analyzeSftpInput_File3(Input $input)
        {
            $input->analyze();
            $this->checkInput($input);

            return $input;
        }

        /**
         * @test
         * @depends getSftpInput_File4
         */
        public function analyzeSftpInput_File4(Input $input)
        {
            $input->analyze();
            $this->checkInput($input);

            return $input;
        }

        // ==== ENCODING ====

        /**
         * @depends analyzeSftpInput_File1
         */
        public function testCreateSftpInputJob_File1(Input $input)
        {
            /* CREATE VIDEO STREAM CONFIG */
            $videoStreamConfig = new VideoStreamConfig();
            $videoStreamConfig->bitrate = 1024000;
            $videoStreamConfig->height = 202;
            $videoStreamConfig->width = 480;

            /* CREATE AUDIO STREAM CONFIGS */
            $audioStreamConfig = new AudioStreamConfig();
            $audioStreamConfig->bitrate = 256000;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = $this->getName() . 'EncodingProfile';
            $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

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

            $this->assertInstanceOf('bitcodin\Job', $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        /**
         * @depends analyzeSftpInput_File2
         */
        public function testCreateSftpInputJob_File2(Input $input)
        {
            /* CREATE VIDEO STREAM CONFIG */
            $videoStreamConfig = new VideoStreamConfig();
            $videoStreamConfig->bitrate = 1024000;
            $videoStreamConfig->height = 202;
            $videoStreamConfig->width = 480;

            /* CREATE AUDIO STREAM CONFIGS */
            $audioStreamConfig = new AudioStreamConfig();
            $audioStreamConfig->bitrate = 256000;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = $this->getName() . 'EncodingProfile';
            $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf('bitcodin\Job', $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        /**
         * @depends analyzeSftpInput_File3
         */
        public function testCreateSftpInputJob_File3(Input $input)
        {
            /* CREATE VIDEO STREAM CONFIG */
            $videoStreamConfig = new VideoStreamConfig();
            $videoStreamConfig->bitrate = 1024000;
            $videoStreamConfig->height = 202;
            $videoStreamConfig->width = 480;

            /* CREATE AUDIO STREAM CONFIGS */
            $audioStreamConfig = new AudioStreamConfig();
            $audioStreamConfig->bitrate = 256000;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = $this->getName() . 'EncodingProfile';
            $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf('bitcodin\Job', $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        /**
         * @depends analyzeSftpInput_File4
         */
        public function testCreateSftpInputJob_File4(Input $input)
        {
            /* CREATE VIDEO STREAM CONFIG */
            $videoStreamConfig = new VideoStreamConfig();
            $videoStreamConfig->bitrate = 1024000;
            $videoStreamConfig->height = 202;
            $videoStreamConfig->width = 480;

            /* CREATE AUDIO STREAM CONFIGS */
            $audioStreamConfig = new AudioStreamConfig();
            $audioStreamConfig->bitrate = 256000;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = $this->getName() . 'EncodingProfile';
            $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf('bitcodin\Job', $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        // ==== WAIT FOR ENCODING TO BE FINISHED ====

        /**
         * @depends testCreateSftpInputJob_File1
         */
        public function testUpdateSftpInputJob_File1(Job $job)
        {
            $this->markTestIncomplete("Escaping isn't final for such filenames");
            $this->updateJob($job);
        }

        /**
         * @depends testCreateSftpInputJob_File2
         */
        public function testUpdateSftpInputJob_File2(Job $job)
        {
            $this->updateJob($job);
        }

        /**
         * @depends testCreateSftpInputJob_File3
         */
        public function testUpdateSftpInputJob_File3(Job $job)
        {
            $this->updateJob($job);
        }

        /**
         * @depends testCreateSftpInputJob_File4
         */
        public function testUpdateSftpInputJob_File4(Job $job)
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