<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../../vendor/autoload.php';

use bitcodin\AudioMetaData;
use bitcodin\Bitcodin;
use bitcodin\Input;
use bitcodin\AudioStreamConfig;
use bitcodin\EncodingProfile;
use bitcodin\ManifestTypes;
use bitcodin\Job;
use bitcodin\HttpInputConfig;
use bitcodin\EncodingProfileConfig;
use bitcodin\JobConfig;
use bitcodin\JobSpeedTypes;
use bitcodin\MergeAudioChannelConfig;
use test\job\AbstractJobTest;
use bitcodin\VideoStreamConfig;

class JobAudioOnlyTest extends AbstractJobTest {

    public function __construct() {
        parent::__construct();

        Bitcodin::setApiToken($this->getApiKey());
    }

    public function testMergeMonoAudioChannelsJob()
    {

        $inputConfig = new HttpInputConfig();
        $inputConfig->url = 'http://bitbucketireland.s3.amazonaws.com/at_test/mono_streams.mkv';
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 512000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;
        $audioStreamConfig->defaultStreamId = 0;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'Merge Audio Channels Config Profile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        $mergeAudioChannelConfig = new MergeAudioChannelConfig();
        $mergeAudioChannelConfig->audioChannels = array(1, 2, 3, 4, 5, 6);

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
        $jobConfig->manifestTypes[] = ManifestTypes::MPD;
        $jobConfig->speed = JobSpeedTypes::STANDARD;
        $jobConfig->mergeAudioChannelConfigs[] = $mergeAudioChannelConfig;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);

        return $job;
    }

    /**
     * @depends testMergeMonoAudioChannelsJob
     */
    public function testUpdateMergeMonoAudioChannelsJob(Job $job)
    {
        return $this->updateJob($job);
    }

    public function testMergeChannelsMultiAudioStreamJob()
    {
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = 'http://bitbucketireland.s3.amazonaws.com/at_test/mono_streams.mkv';
        $input = Input::create($inputConfig);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 512000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig1 = new AudioStreamConfig();
        $audioStreamConfig1->bitrate = 256000;
        $audioStreamConfig1->defaultStreamId = 0;

        $audioStreamConfig2 = new AudioStreamConfig();
        $audioStreamConfig2->bitrate = 128000;
        $audioStreamConfig2->defaultStreamId = 1;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'Multi Audio Stream Profile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig1;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig2;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        $audioMetaData12 = new AudioMetaData();
        $audioMetaData12->defaultStreamId = 0;
        $audioMetaData12->label = 'Channel 1 and 2';
        $audioMetaData12->language = 'en';

        $audioMetaData34 = new AudioMetaData();
        $audioMetaData34->defaultStreamId = 1;
        $audioMetaData34->label = 'Channel 3 and 4';
        $audioMetaData34->language = 'en';

        $mergeAudioChannelConfig12 = new MergeAudioChannelConfig();
        $mergeAudioChannelConfig12->audioChannels = array(1, 2);
        $mergeAudioChannelConfig34 = new MergeAudioChannelConfig();
        $mergeAudioChannelConfig34->audioChannels = array(3, 4);

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
        $jobConfig->manifestTypes[] = ManifestTypes::MPD;
        $jobConfig->speed = JobSpeedTypes::STANDARD;
        $jobConfig->audioMetaData[] = $audioMetaData12;
        $jobConfig->audioMetaData[] = $audioMetaData34;
        $jobConfig->mergeAudioChannelConfigs[] = $mergeAudioChannelConfig12;
        $jobConfig->mergeAudioChannelConfigs[] = $mergeAudioChannelConfig34;

        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);

        return $job;
    }

    /**
     * @depends testMergeChannelsMultiAudioStreamJob
     */
    public function testUpdateMergeChannelsMultiAudioStreamJob(Job $job)
    {
        return $this->updateJob($job);
    }

}
