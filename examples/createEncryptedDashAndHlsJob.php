<?php
/**
 * User: msmole
 * Date: 19.08.15
 * Time: 20:20
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
use bitcodin\CombinedWidevinePlayreadyDRMConfig;
use bitcodin\JobSpeedTypes;
use bitcodin\DRMEncryptionMethods;
use bitcodin\HLSEncryptionConfig;
use bitcodin\HLSEncryptionMethods;

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

/* CREATE COMBINED WIDEVINE PLAYREADY DRM CONFIG */
$combinedWidevinePlayreadyDRMConfig = new CombinedWidevinePlayreadyDRMConfig();
$combinedWidevinePlayreadyDRMConfig->pssh = 'CAESEOtnarvLNF6Wu89hZjDxo9oaDXdpZGV2aW5lX3Rlc3QiEGZrajNsamFTZGZhbGtyM2oqAkhEMgA=';
$combinedWidevinePlayreadyDRMConfig->key = '100b6c20940f779a4589152b57d2dacb';
$combinedWidevinePlayreadyDRMConfig->kid = 'eb676abbcb345e96bbcf616630f1a3da';
$combinedWidevinePlayreadyDRMConfig->laUrl = 'http://playready.directtaps.net/pr/svc/rightsmanager.asmx?PlayRight=1&ContentKey=EAtsIJQPd5pFiRUrV9Layw==';
$combinedWidevinePlayreadyDRMConfig->method =  DRMEncryptionMethods::MPEG_CENC;

$hlsEncryptionConfig = new HLSEncryptionConfig();
$hlsEncryptionConfig->method = HLSEncryptionMethods::AES_128;
$hlsEncryptionConfig->key = 'cab5b529ae28d5cc5e3e7bc3fd4a544d';
$hlsEncryptionConfig->iv = '08eecef4b026deec395234d94218273d';

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->speed = JobSpeedTypes::STANDARD;
$jobConfig->drmConfig = $combinedWidevinePlayreadyDRMConfig;
$jobConfig->hlsEncryptionConfig = $hlsEncryptionConfig;

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
