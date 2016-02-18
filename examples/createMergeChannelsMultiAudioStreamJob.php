<?php

use bitcodin\AudioMetaData;
use bitcodin\Bitcodin;
use bitcodin\JobSpeedTypes;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\Job;
use bitcodin\JobConfig;
use bitcodin\Input;
use bitcodin\HttpInputConfig;
use bitcodin\EncodingProfile;
use bitcodin\EncodingProfileConfig;
use bitcodin\ManifestTypes;
use bitcodin\MergeAudioChannelConfig;

require_once __DIR__.'/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://bitbucketireland.s3.amazonaws.com/at_test/mono_streams.mkv';
$input = Input::create($inputConfig);

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'MyApiTestEncodingProfile';

/* CREATE VIDEO STREAM CONFIGS */
$videoStreamConfig1 = new VideoStreamConfig();
$videoStreamConfig1->bitrate = 4800000;
$videoStreamConfig1->height = 1080;
$videoStreamConfig1->width = 1920;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig1;

$videoStreamConfig2 = new VideoStreamConfig();
$videoStreamConfig2->bitrate = 2400000;
$videoStreamConfig2->height = 720;
$videoStreamConfig2->width = 1280;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig2;

$videoStreamConfig3 = new VideoStreamConfig();
$videoStreamConfig3->bitrate = 1200000;
$videoStreamConfig3->height = 480;
$videoStreamConfig3->width = 854;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig3;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig1 = new AudioStreamConfig();
$audioStreamConfig1->bitrate = 256000;
$audioStreamConfig1->defaultStreamId = 0;

$audioStreamConfig2 = new AudioStreamConfig();
$audioStreamConfig2->bitrate = 128000;
$audioStreamConfig2->defaultStreamId = 1;

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

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    sleep(1);
} while($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);