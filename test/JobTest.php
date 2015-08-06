<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/BitcodinApiTestBaseClass.php';

use bitcodin\Input;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\EncodingProfile;
use bitcodin\ManifestTypes;
use bitcodin\Job;
use bitcodin\HttpInputConfig;
use bitcodin\EncodingProfileConfig;
use bitcodin\JobConfig;
use bitcodin\Output;
use bitcodin\S3OutputConfig;
use bitcodin\WidevineDRMConfig;
use bitcodin\DRMEncryptionMethods;
use bitcodin\JobSpeedTypes;
use bitcodin\PlayReadyDRMConfig;
use bitcodin\CombinedWidevinePlayreadyDRMConfig;

class JobTest extends BitcodinApiTestBaseClass {

    const URL_FILE = 'http://bitbucketireland.s3.amazonaws.com/h264_720p_mp_3.1_3mbps_aac_shrinkage.mp4';

    /** TEST JOB CREATION */

    public function testCreateWidevineDRMJob()
    {
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 480;
        $videoStreamConfig->width = 202;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'MyApiTestEncodingProfile';
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

    public function testCreatePlayreadyDRMJob()
    {
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 480;
        $videoStreamConfig->width = 202;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'MyApiTestEncodingProfile';
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

    public function testCreateCombinedWidevinePlayreadyDRMJob()
    {
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 480;
        $videoStreamConfig->width = 202;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'MyApiTestEncodingProfile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        /* CREATE COMBINED WIDEVINE PLAYREADY DRM CONFIG */
        $combinedWidevinePlayreadyDRMConfig = new CombinedWidevinePlayreadyDRMConfig();
        $combinedWidevinePlayreadyDRMConfig->pssh = '#CAESEOtnarvLNF6Wu89hZjDxo9oaDXdpZGV2aW5lX3Rlc3QiEGZrajNsamFTZGZhbGtyM2oqAkhEMgA=';
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

    public function testCreateJob()
    {
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 480;
        $videoStreamConfig->width = 202;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'MyApiTestEncodingProfile';
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
     * @depends JobTest::testCreateWidevineDRMJob
     */
    public function testUpdateWidevineDRMJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /**
     * @depends JobTest::testCreatePlayreadyDRMJob
     */
    public function testUpdatePlayreadyDRMJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /**
     * @depends JobTest::testCreateCombinedWidevinePlayreadyDRMJob
     */
    public function testUpdateCombinedWidevinePlayreadyDRMJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /**
     * @depends JobTest::testCreateHLSEncryptionJob
     */
    public function testUpdateHLSEncryptionJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /**
     * @depends JobTest::testCreateHLSEncryptionJobWithoutIV
     */
    public function testUpdateHLSEncryptionJobWithoutIV(Job $job)
    {
        return $this->updateJob($job);
    }

    /**
     * @depends JobTest::testCreateJob
     */
    public function testUpdateJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /** TEST JOB TRANSFER */

    /**
     * @depends JobTest::testUpdateWidevineDRMJob
     */
    public function testTransferWidevineDRMJob(Job $job)
    {
        $this->transferJob($job);
    }

    /**
     * @depends JobTest::testUpdatePlayreadyDRMJob
     */
    public function testTransferPlayreadyDRMJob(Job $job)
    {
        $this->transferJob($job);
    }

    /**
     * @depends JobTest::testUpdateCombinedWidevinePlayreadyDRMJob
     */
    public function testTransferCombinedWidevinePlayreadyDRMJob(Job $job)
    {
        $this->transferJob($job);
    }

    /**
     * @depends JobTest::testUpdateHLSEncryptionJob
     */
    public function testTransferHLSEncryptionJob(Job $job)
    {
        $this->transferJob($job);
    }

    /**
     * @depends JobTest::testUpdateHLSEncryptionJobWithoutIV
     */
    public function testTransferHLSEncryptionJobWithoutIV(Job $job)
    {
        $this->transferJob($job);
    }

    /**
     * @depends JobTest::testUpdateJob
     */
    public function testTransferJob(Job $job)
    {
        $this->transferJob($job);
    }

    public function testGetNoneExistingJob()
    {
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

    /** HELPER METHODS **/

    public function updateJob(Job $job)
    {
        /* WAIT TIL JOB IS FINISHED */
        do{
            $job->update();
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);
            sleep(1);
        } while($job->status != Job::STATUS_FINISHED);

        $this->assertEquals($job->status, Job::STATUS_FINISHED);

        return $job;
    }

    public function transferJob(Job $job)
    {
        $s3Config = $this->getKey('s3');
        $outputConfig = new S3OutputConfig();
        $outputConfig->accessKey = $s3Config->accessKey;
        $outputConfig->secretKey = $s3Config->secretKey;
        $outputConfig->name = $s3Config->name;
        $outputConfig->bucket = $s3Config->bucket;
        $outputConfig->region = $s3Config->region;
        $outputConfig->makePublic = false;

        $output = Output::create($outputConfig);
        /* WAIT TIL JOB IS FINISHED */
        $job->transfer($output);
    }
}
