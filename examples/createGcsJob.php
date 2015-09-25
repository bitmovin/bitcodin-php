<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 03.09.15
 * Time: 14:59
 */

use bitcodin\AwsRegion;
use bitcodin\Bitcodin;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\Job;
use bitcodin\JobConfig;
use bitcodin\Input;
use bitcodin\S3InputConfig;
use bitcodin\EncodingProfile;
use bitcodin\EncodingProfileConfig;
use bitcodin\ManifestTypes;
use bitcodin\Output;
use bitcodin\GcsOutputConfig;

require_once __DIR__.'/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new S3InputConfig();
$inputConfig->accessKey = 'yourAWSAccessKey';
$inputConfig->secretKey = 'yourAWSSecretKey';
$inputConfig->bucket    = 'yourBucketName';
$inputConfig->region    = AwsRegion::EU_WEST_1;             // bucket region
$inputConfig->objectKey = 'path/to/your/fileonbucket.mp4';
$inputConfig->host      = 's3-eu-west-1.amazonaws.com';      // OPTIONAL
$input = Input::create($inputConfig);

/* CREATE OUTPUT CONFIG  */
$outputConfig = new GcsOutputConfig();
$outputConfig->name         = "TestGcsOutput";
$outputConfig->accessKey    = "yourGcsAccessKey";
$outputConfig->secretKey    = "yourGcsSecretKey";
$outputConfig->bucket       = "yourBucketName";
$outputConfig->prefix       = "path/to/your/outputDirectory";
$outputConfig->makePublic   = false;                            // This flag determines whether the files put on S3 will be publicly accessible via HTTP Url or not

$output = Output::create($outputConfig);

/* CREATE VIDEO STREAM CONFIG */
$videoStreamConfig = new VideoStreamConfig();
$videoStreamConfig->bitrate = 512000;
$videoStreamConfig->height = 202;
$videoStreamConfig->width = 480;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig = new AudioStreamConfig();
$audioStreamConfig->bitrate = 128000;

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'MyApiTestEncodingProfile';
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->output = $output;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;

/* CREATE JOB */
$job = Job::create($jobConfig);

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    sleep(1);
} while($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);
