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
use bitcodin\WidevineDRMConfig;
use bitcodin\JobSpeedTypes;
use bitcodin\DRMEncryptionMethods;

require_once __DIR__.'/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

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
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->speed = JobSpeedTypes::STANDARD;
$jobConfig->drmConfig = $widevineDRMConfig;

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