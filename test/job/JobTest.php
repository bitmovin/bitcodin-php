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


class JobTest extends AbstractJobTest {

    const URL_FILE = 'http://bitbucketireland.s3.amazonaws.com/h264_720p_mp_3.1_3mbps_aac_shrinkage.mp4';

    /** TEST JOB CREATION */
    public function testCreateWidevineDRMJob()
    {
        Bitcodin::setApiToken($this->getApiKey());
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
        Bitcodin::setApiToken($this->getApiKey());
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
        Bitcodin::setApiToken($this->getApiKey());
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
        Bitcodin::setApiToken($this->getApiKey());
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
     * @depends testCreateWidevineDRMJob
     */
    public function testUpdateWidevineDRMJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /**
     * @depends testCreatePlayreadyDRMJob
     */
    public function testUpdatePlayreadyDRMJob(Job $job)
    {
        return $this->updateJob($job);
    }

    /**
     * @depends testCreateCombinedWidevinePlayreadyDRMJob
     */
    public function testUpdateCombinedWidevinePlayreadyDRMJob(Job $job)
    {
        return $this->updateJob($job);
    }


    /**
     * @depends testCreateJob
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
