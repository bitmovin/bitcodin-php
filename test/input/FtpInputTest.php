<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 11.11.15
 * Time: 14:10
 */

namespace test\input;

require_once __DIR__ . '/../../vendor/autoload.php';

use bitcodin\AudioStreamConfig;
use bitcodin\Bitcodin;
use bitcodin\EncodingProfile;
use bitcodin\EncodingProfileConfig;
use bitcodin\FtpInputConfig;
use bitcodin\Input;
use bitcodin\Job;
use bitcodin\JobConfig;
use bitcodin\ManifestTypes;
use bitcodin\VideoStreamConfig;
use DateTime;
use stdClass;
use test\BitcodinApiTestBaseClass;

class FtpInputTest extends BitcodinApiTestBaseClass {

    const USER1_FTP_FILE_1      = '/content/sintel![](){}<>?*&$,-short.mkv';
    const USER1_FTP_FILE_2      = '/content/sintel&short.mkv';
    const USER1_FTP_FILE_3      = '/content/sintel-short.mkv';

    const USER2_FTP_FILE_1      = '/content/sintel-original-short.mkv';

    public function __construct() {
        parent::__construct();

        Bitcodin::setApiToken($this->getApiKey());
    }

    public function testCreateFtpInput_File1()
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

    public function testCreateFtpInput_File2()
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

    public function testCreateFtpInput_File3()
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

    public function testCreateFtpInput_File4()
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

    /**
     * @depends testCreateFtpInput_File1
     */
    public function testUpdateFtpInput_File1(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends testCreateFtpInput_File2
     */
    public function testUpdateFtpInput_File2(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends testCreateFtpInput_File3
     */
    public function testUpdateFtpInput_File3(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends testCreateFtpInput_File4
     */
    public function testUpdateFtpInput_File4(Input $input)
    {
        $input->update();
        $this->checkInput($input);
        return $input;
    }

    /**
     * @depends testUpdateFtpInput_File1
     */
    public function testGetFtpInput_File1(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends testUpdateFtpInput_File2
     */
    public function testGetFtpInput_File2(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends testUpdateFtpInput_File3
     */
    public function testGetFtpInput_File3(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends testUpdateFtpInput_File4
     */
    public function testGetFtpInput_File4(Input $input)
    {
        $inputGot = Input::get($input->inputId);
        $this->checkInput($inputGot);

        return $inputGot;
    }

    /**
     * @depends testGetFtpInput_File1
     */
    public function testAnalyzeFtpInput_File1(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends testGetFtpInput_File2
     */
    public function testAnalyzeFtpInput_File2(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends testGetFtpInput_File3
     */
    public function testAnalyzeFtpInput_File3(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends testGetFtpInput_File4
     */
    public function testAnalyzeFtpInput_File4(Input $input)
    {
        $input->analyze();
        $this->checkInput($input);

        return $input;
    }

    /**
     * @depends testAnalyzeFtpInput_File1
     */
    public function testCreateFtpInputJob_File1(Input $input)
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
        $encodingProfileConfig->name = $this->getName().'EncodingProfile';
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
     * @depends testAnalyzeFtpInput_File2
     */
    public function testCreateFtpInputJob_File2(Input $input)
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
        $encodingProfileConfig->name = $this->getName().'EncodingProfile';
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
     * @depends testAnalyzeFtpInput_File3
     */
    public function testCreateFtpInputJob_File3(Input $input)
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
        $encodingProfileConfig->name = $this->getName().'EncodingProfile';
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
     * @depends testAnalyzeFtpInput_File4
     */
    public function testCreateFtpInputJob_File4(Input $input)
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
        $encodingProfileConfig->name = $this->getName().'EncodingProfile';
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
     * @depends testCreateFtpInputJob_File1
     */
    public function testUpdateFtpInputJob_File1(Job $job)
    {
        $this->markTestIncomplete("");
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

    /** HELPER METHODS **/
    private function checkInput(Input $input)
    {
        $this->assertInstanceOf('bitcodin\Input', $input);
        $this->assertNotNull($input->inputId);
        $this->assertTrue(is_numeric($input->inputId), 'inputId');
        $this->assertTrue(is_string($input->filename), 'filename');
        $this->assertTrue(is_string($input->thumbnailUrl), 'thumbnailUrl');
        $this->assertTrue(is_string($input->inputType), 'inputType ');
        $this->assertTrue(is_array($input->mediaConfigurations), 'mediaConfigurations');
    }

    protected function updateJob(Job $job)
    {
        /* WAIT TIL JOB IS FINISHED */
        $this->waitTillJobGetsExpectedStatus($job, Job::STATUS_FINISHED, Job::STATUS_ERROR);
        $this->assertEquals($job->status, Job::STATUS_FINISHED);

        return $job;
    }

    protected function updateJobError(Job $job)
    {
        $this->waitTillJobGetsExpectedStatus($job, Job::STATUS_ERROR, Job::STATUS_FINISHED);
        $this->assertEquals($job->status, Job::STATUS_ERROR);

        return $job;
    }

    private function waitTillJobGetsExpectedStatus(Job $job, $expectedStatus, $notExpectedStatus, $timeOutSeconds = 1000)
    {
        $expireTime = (new DateTime())->add(new \DateInterval('PT'.$timeOutSeconds.'S'));
        do{
            sleep(2);
            $job->update();
            $this->assertNotEquals($job->status, $notExpectedStatus);
            $this->assertTrue($expireTime >= new DateTime(), 'Timeout during job update!');

        } while($job->status != $expectedStatus);
    }

}