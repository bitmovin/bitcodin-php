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
use bitcodin\Output;
use bitcodin\FtpOutputConfig;

require_once __DIR__.'/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('INSERT_YOUR_API_KEY_HERE'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://bitbucketireland.s3.amazonaws.com/Sintel-two-audio-streams-short.mkv';
$input = Input::create($inputConfig);

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'Your first Encoding Profile';

/* CREATE VIDEO STREAM CONFIGS */
$videoStreamConfig1 = new VideoStreamConfig();
$videoStreamConfig1->bitrate = 4800000;
//Omitting the height or width option tells our system to keep the aspect ratio of your input file for your encoded content.
//$videoStreamConfig1->height = 1080;
$videoStreamConfig1->width = 1920;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig1;

$videoStreamConfig2 = new VideoStreamConfig();
$videoStreamConfig2->bitrate = 2400000;
//$videoStreamConfig2->height = 720;
$videoStreamConfig2->width = 1280;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig2;

$videoStreamConfig3 = new VideoStreamConfig();
$videoStreamConfig3->bitrate = 1200000;
//$videoStreamConfig3->height = 480;
$videoStreamConfig3->width = 854;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig3;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfigTrack1 = new AudioStreamConfig();
$audioStreamConfigTrack1->bitrate = 128000;
$audioStreamConfigTrack1->defaultStreamId = 0; // 0 = apply this StreamConfig to the first Audio Stream

$audioStreamConfigTrack2 = new AudioStreamConfig();
$audioStreamConfigTrack2->bitrate = 128000;
$audioStreamConfigTrack2->defaultStreamId = 1; // 1 = apply this StreamConfig to the second Audio Stream

$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigTrack1;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigTrack2;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$audioMetaDataTrack1 = new AudioMetaData();
$audioMetaDataTrack1->defaultStreamId = 0;
$audioMetaDataTrack1->label = 'Deutsch';
$audioMetaDataTrack1->language = 'de';

$audioMetaDataTrack2 = new AudioMetaData();
$audioMetaDataTrack2->defaultStreamId = 1;
$audioMetaDataTrack2->label = 'English';
$audioMetaDataTrack2->language = 'en';

$jobConfig = new JobConfig();
$jobConfig->speed = JobSpeedTypes::PREMIUM;
$jobConfig->input = $input;
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->audioMetaData[] = $audioMetaDataTrack1;
$jobConfig->audioMetaData[] = $audioMetaDataTrack2;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;

/* CREATE JOB */
$job = Job::create($jobConfig);

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    sleep(1);
} while($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);
