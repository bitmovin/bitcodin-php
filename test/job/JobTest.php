<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */

namespace test\job;

require_once __DIR__ . '/../../vendor/autoload.php';


use bitcodin\Bitcodin;
use bitcodin\Input;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\EncodingProfile;
use bitcodin\ManifestTypes;
use bitcodin\Job;
use bitcodin\HttpInputConfig;
use bitcodin\EncodingProfileConfig;
use bitcodin\JobConfig;
use bitcodin\WidevineDRMConfig;
use bitcodin\DRMEncryptionMethods;
use bitcodin\JobSpeedTypes;
use bitcodin\PlayReadyDRMConfig;
use bitcodin\CombinedWidevinePlayreadyDRMConfig;
use bitcodin\HLSEncryptionConfig;
use bitcodin\HLSEncryptionMethods;
use bitcodin\exceptions\BitcodinException;


class JobTest extends AbstractJobTest {

    const URL_FILE = 'http://bitbucketireland.s3.amazonaws.com/Sintel-original-short.mkv';

    /** TEST JOB CREATION */

    /**
     * @test
     */
    public function createWidevineDRMJob()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->width = 480;
        $videoStreamConfig->height = 202;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'WidevineDRMJobEncodingProfile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        /* CREATE DRM WIDEVINE CONFIG */
        $widevineDRMConfig = new WidevineDRMConfig();
        $widevineDRMConfig->requestUrl = 'http://license.uat.widevine.com/cenc/getcontentkey';
        $widevineDRMConfig->signingKey = '1ae8ccd0e7985cc0b6203a55855a1034afc252980e970ca90e5202689f947ab9';
        $widevineDRMConfig->signingIV = 'd58ce954203b7c9a9a9d467f59839249';
        $widevineDRMConfig->contentId = '746573745f69645f4639465043304e4f';
        $widevineDRMConfig->provider = 'widevine_test';
        $widevineDRMConfig->method = DRMEncryptionMethods::MPEG_CENC;

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::MPD;
        $jobConfig->speed = JobSpeedTypes::STANDARD;
        $jobConfig->drmConfig = $widevineDRMConfig;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }

    /**
     * @test
     * @expectedException               bitcodin\exceptions\BitcodinException
     */
    public function createFramerateJobTest()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;
        $videoStreamConfig->rate = 12;

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
        $jobConfig->manifestTypes[] = ManifestTypes::MPD;
        $jobConfig->speed = JobSpeedTypes::PREMIUM;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        return $job;
    }

    /**
     *@test
     */
    public function createPlayreadyDRMJob()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'PlayreadyDRMEncodingProfile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        /* CREATE DRM PLAYREADY CONFIG */
        $playreadyDRMConfig = new PlayReadyDRMConfig();
        $playreadyDRMConfig->key = '';
        $playreadyDRMConfig->keySeed = 'XVBovsmzhP9gRIZxWfFta3VVRPzVEWmJsazEJ46I';
        $playreadyDRMConfig->kid = '746573745f69645f4639465043304e4f';
        $playreadyDRMConfig->laUrl = 'http://playready.directtaps.net/pr/svc/rightsmanager.asmx';
        $playreadyDRMConfig->method =  DRMEncryptionMethods::MPEG_CENC;

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::MPD;
        $jobConfig->speed = JobSpeedTypes::STANDARD;
        $jobConfig->drmConfig = $playreadyDRMConfig;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }

    /**
     * @test
     */
    public function createCombinedWidevinePlayreadyDRMJob()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;


        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'CombinedWidevinePlayready';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        /* CREATE COMBINED WIDEVINE PLAYREADY DRM CONFIG */
        $combinedWidevinePlayreadyDRMConfig = new CombinedWidevinePlayreadyDRMConfig();
        $combinedWidevinePlayreadyDRMConfig->pssh = 'CAESEOtnarvLNF6Wu89hZjDxo9oaDXdpZGV2aW5lX3Rlc3QiEGZrajNsamFTZGZhbGtyM2oqAkhEMgA=';
        $combinedWidevinePlayreadyDRMConfig->key = '100b6c20940f779a4589152b57d2dacb';
        $combinedWidevinePlayreadyDRMConfig->kid = 'eb676abbcb345e96bbcf616630f1a3da';
        $combinedWidevinePlayreadyDRMConfig->laUrl = 'http://playready.directtaps.net/pr/svc/rightsmanager.asmx?PlayRight=1&ContentKey=EAtsIJQPd5pFiRUrV9Layw==';
        $combinedWidevinePlayreadyDRMConfig->method =  DRMEncryptionMethods::MPEG_CENC;

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::MPD;
        $jobConfig->speed = JobSpeedTypes::STANDARD;
        $jobConfig->drmConfig = $combinedWidevinePlayreadyDRMConfig;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }

    /**
     * @test
     * @return Job
     */
    public function createHLSEncryptionJob()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'HLSEncryption';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        /* CREATE HLS ENCRYPTION CONFIG */
        $hlsEncryptionConfig = new HLSEncryptionConfig();
        $hlsEncryptionConfig->method = HLSEncryptionMethods::SAMPLE_AES;
        $hlsEncryptionConfig->key = 'cab5b529ae28d5cc5e3e7bc3fd4a544d';  // must be 16 byte hexadecimal
        $hlsEncryptionConfig->iv = '08eecef4b026deec395234d94218273d';   // must be 16 byte hexadecimal - will be created randomly if missing

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
        $jobConfig->speed = JobSpeedTypes::STANDARD;
        $jobConfig->hlsEncryptionConfig = $hlsEncryptionConfig;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }

    /**
     * @test
     * @return Job
     */
    public function createHLSEncryptionJobWithoutIV()
    {
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'HLSEncryptionJobWithoutIVEncodingProfile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        /* CREATE HLS ENCRYPTION CONFIG */
        $hlsEncryptionConfig = new HLSEncryptionConfig();
        $hlsEncryptionConfig->method = HLSEncryptionMethods::SAMPLE_AES;
        $hlsEncryptionConfig->key = 'cab5b529ae28d5cc5e3e7bc3fd4a544d';  // must be 16 byte hexadecimal

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
        $jobConfig->speed = JobSpeedTypes::STANDARD;
        $jobConfig->hlsEncryptionConfig = $hlsEncryptionConfig;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }

    /**
     * @test
     * @return Job
     */
    public function createAES128HLSEncryptionJob()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'MyApiTestEncodingProfile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        /* CREATE HLS ENCRYPTION CONFIG */
        $hlsEncryptionConfig = new HLSEncryptionConfig();
        $hlsEncryptionConfig->method = HLSEncryptionMethods::AES_128;
        $hlsEncryptionConfig->key = 'cab5b529ae28d5cc5e3e7bc3fd4a544d';  // must be 16 byte hexadecimal
        $hlsEncryptionConfig->iv = '08eecef4b026deec395234d94218273d';   // must be 16 byte hexadecimal - will be created randomly if missing

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
        $jobConfig->speed = JobSpeedTypes::STANDARD;
        $jobConfig->hlsEncryptionConfig = $hlsEncryptionConfig;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }

    /**
     * @test
     * @return Job
     */
    public function createAES128HLSEncryptionJobWithoutIV()
    {
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'MyApiTestEncodingProfile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        /* CREATE HLS ENCRYPTION CONFIG */
        $hlsEncryptionConfig = new HLSEncryptionConfig();
        $hlsEncryptionConfig->method = HLSEncryptionMethods::AES_128;
        $hlsEncryptionConfig->key = 'cab5b529ae28d5cc5e3e7bc3fd4a544d';  // must be 16 byte hexadecimal

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
        $jobConfig->speed = JobSpeedTypes::STANDARD;
        $jobConfig->hlsEncryptionConfig = $hlsEncryptionConfig;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }

    /**
     * @test
     * @return Job
     */
    public function createJob()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

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

    /** TEST JOB PROGRESS*/

    /**
     * @depends createWidevineDRMJob
     */
    public function testUpdateWidevineDRMJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /**
     * @depends createPlayreadyDRMJob
     */
    public function testUpdatePlayreadyDRMJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /**
     * @depends createCombinedWidevinePlayreadyDRMJob
     */
    public function testUpdateCombinedWidevinePlayreadyDRMJob(Job $job)
    {
        return $this->updateJob($job);
    }


    /**
     * @depends createJob
     */
    public function testUpdateJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /** TEST JOB TRANSFER */

    /**
     * @depends testUpdateWidevineDRMJob
     */
    public function testTransferWidevineDRMJob(Job $job)
    {
        $this->transferJob($job);
    }

    /**
     * @depends testUpdatePlayreadyDRMJob
     */
    public function testTransferPlayreadyDRMJob(Job $job)
    {
        $this->transferJob($job);
    }

    /**
     * @depends testUpdateCombinedWidevinePlayreadyDRMJob
     */
    public function testTransferCombinedWidevinePlayreadyDRMJob(Job $job)
    {
        $this->transferJob($job);
    }

    /**
     * @depends testUpdateJob
     */
    public function testTransferJob(Job $job)
    {
        $this->transferJob($job);
    }

    public function testGetNoneExistingJob()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $this->setExpectedException('bitcodin\exceptions\BitcodinResourceNotFoundException');
        Job::get(0);
    }

    public function testListAllJobs()
    {
        /* GET LIST OF JOBS */
        foreach(Job::getListAll() as $job)
        {
            $this->assertNotNull($job->jobId);

            $this->assertTrue(in_array($job->status,
                [Job::STATUS_FINISHED, Job::STATUS_ENQUEUED, Job::STATUS_IN_PROGRESS, Job::STATUS_ERROR]
            ), "Invalid job status: " . $job->status);
        }
    }

}
