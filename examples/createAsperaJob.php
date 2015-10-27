<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 22.10.15
 * Time: 15:31
 */

require_once __DIR__.'/../vendor/autoload.php';

use bitcodin\AudioStreamConfig;
use bitcodin\AsperaInputConfig;
use bitcodin\AzureOutputConfig;
use bitcodin\Bitcodin;
use bitcodin\EncodingProfile;
use bitcodin\EncodingProfileConfig;
use bitcodin\Input;
use bitcodin\Job;
use bitcodin\JobConfig;
use bitcodin\JobSpeedTypes;
use bitcodin\ManifestTypes;
use bitcodin\Output;
use bitcodin\VideoStreamConfig;

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiToken'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API
$asperaUrl  = 'yourAccountKey';
$minBandwidth = '100k';
$maxBandwidth = '3g';

$inputConfig = new AsperaInputConfig();
$inputConfig->url = $asperaUrl;
$inputConfig->minBandwidth = $minBandwidth;
$inputConfig->maxBandwidth = $maxBandwidth;

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

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->speed = JobSpeedTypes::STANDARD;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;

/* CREATE JOB */
$job = Job::create($jobConfig);

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    sleep(1);
} while($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);

$outputConfig = new AzureOutputConfig();
$outputConfig->name = "TestAzureOutput";
$outputConfig->container = $azureBlobStorageContainer;
$outputConfig->accountKey = $azureBlobStorageAccountKey;
$outputConfig->accountName = $azureBlobStorageAccountName;

$azureOutput = Output::create($outputConfig);

$job->transfer($azureOutput);
