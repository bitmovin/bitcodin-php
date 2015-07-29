<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/BitcodinApiTestBaseClass.php';

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
use bitcodin\Output;
use bitcodin\S3OutputConfig;
use bitcodin\WidevineDRMConfig;
use bitcodin\DRMEncryptionMethods;
use bitcodin\JobSpeedTypes;

class JobTest extends BitcodinApiTestBaseClass {

    const URL_FILE = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';

    public function testCreateDRMJob()
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

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }


    /**
     * @depends JobTest::testCreateJob
     */
    public function testUpdateJob(Job $job)
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

    /**
     * @depends JobTest::testUpdateJob
     */
    public function testTransferJob(Job $job)
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
}
