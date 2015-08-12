<?php
/**
 * Created by PhpStorm.
 * User: doweinberger
 * Date: 01.07.15
 * Time: 15:31
 */


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
use bitcodin\Output;
use bitcodin\FtpOutputConfig;
use bitcodin\PlayReadyDRMConfig;
use bitcodin\JobSpeedTypes;
use bitcodin\DRMEncryptionMethods;

require_once __DIR__.'/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';
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
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->speed = JobSpeedTypes::STANDARD;
$jobConfig->drmConfig = $playreadyDRMConfig;

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
    return json_decode(file_get_contents(__DIR__.'/test/config.json'))->{$key};
}