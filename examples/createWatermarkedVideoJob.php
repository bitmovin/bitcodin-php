<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 01.07.15
 * Time: 15:31
 */


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
use bitcodin\WatermarkConfig;

require_once __DIR__.'/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';

$input = Input::create($inputConfig);

/* CREATE VIDEO STREAM CONFIG */
$videoStreamConfig = new VideoStreamConfig();
$videoStreamConfig->bitrate = 2024000;
$videoStreamConfig->height = 818;
$videoStreamConfig->width = 1920;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig = new AudioStreamConfig();
$audioStreamConfig->bitrate = 156000;
$audioStreamConfig->defaultStreamId = 0;

/* CREATE WATERMARK */
$watermarkConfig = new WatermarkConfig();
$watermarkConfig->bottom = 200; // Watermark will be placed with a distance of 200 pixel to the bottom of the input video
$watermarkConfig->right = 100;  // Watermark will be placed with a distance of 100 pixel to the right of the input video
$watermarkConfig->image = 'http://bitdash-a.akamaihd.net/webpages/bitcodin/images/bitcodin-bitmovin-logo-small.png';

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'MyApiTestEncodingProfile';
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;
$encodingProfileConfig->watermarkConfig = $watermarkConfig;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->speed = JobSpeedTypes::STANDARD;
$jobConfig->input = $input;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;


/* CREATE JOB */
$job = Job::create($jobConfig);

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    sleep(1);
} while($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);


$outputConfig = new FtpOutputConfig();
$outputConfig->name = "TestS3Output";
$outputConfig->host = str_replace('ftp://', '', getKey('ftpServer'));
$outputConfig->username = getKey('ftpUser');
$outputConfig->password = getKey('ftpPassword');

$output = Output::create($outputConfig);

/* TRANSFER JOB OUTPUT */
$job->transfer($output);

/* HELPER FUNCTION */
function getKey($key)
{
    return json_decode(file_get_contents(__DIR__.'/../test/config.json'))->{$key};
}