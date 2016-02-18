<?php

use bitcodin\Bitcodin;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\Job;
use bitcodin\JobConfig;
use bitcodin\Input;
use bitcodin\HttpInputConfig;
use bitcodin\EncodingProfile;
use bitcodin\EncodingProfileConfig;
use bitcodin\ManifestTypes;
use bitcodin\Thumbnail;
use bitcodin\ThumbnailConfig;


require_once __DIR__.'/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('INSERT YOUR API KEY'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';
$input = Input::create($inputConfig);

/* CREATE VIDEO STREAM CONFIGS */
$videoStreamConfig1 = new VideoStreamConfig();
$videoStreamConfig1->bitrate = 4800000;
$videoStreamConfig1->height = 1080;
$videoStreamConfig1->width = 1920;

$videoStreamConfig2 = new VideoStreamConfig();
$videoStreamConfig2->bitrate = 2400000;
$videoStreamConfig2->height = 720;
$videoStreamConfig2->width = 1280;

$videoStreamConfig3 = new VideoStreamConfig();
$videoStreamConfig3->bitrate = 1200000;
$videoStreamConfig3->height = 480;
$videoStreamConfig3->width = 854;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig = new AudioStreamConfig();
$audioStreamConfig->bitrate = 128000;

/* CREATE ENCODING PROFILE */
$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'MyApiTestEncodingProfile';
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

/* CREATE JOBCONFIG */
$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;

/* CREATE JOB */
$job = Job::create($jobConfig);

/* CREATE THUMBNAIL AT SECOND 50 */
$thumbnailConfig = new ThumbnailConfig();
$thumbnailConfig->jobId = $job->jobId;
$thumbnailConfig->height = 320;
$thumbnailConfig->position = 50;

/* PRINT OUT THUMBNAIL URL */
$thumbnail = Thumbnail::create($thumbnailConfig);
echo "Thumbnail URL: " . $thumbnail->thumbnailUrl ."\n";