<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 01.07.15
 * Time: 15:31
 */


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
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://bitbucketireland.s3.amazonaws.com/Sintel-two-audio-streams-short.mkv';
$input = Input::create($inputConfig);

/* CREATE VIDEO STREAM CONFIG */
$videoStreamConfig = new VideoStreamConfig();
$videoStreamConfig->bitrate = 512000;
$videoStreamConfig->height = 202;
$videoStreamConfig->width = 480;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfigSoundHigh = new AudioStreamConfig();
$audioStreamConfigSoundHigh->bitrate = 256000;
$audioStreamConfigSoundHigh->defaultStreamId = 0;

$audioStreamConfigSoundLow = new AudioStreamConfig();
$audioStreamConfigSoundLow->bitrate = 128000;
$audioStreamConfigSoundLow->defaultStreamId = 0;

$audioStreamConfigSoundAndVoiceHigh = new AudioStreamConfig();
$audioStreamConfigSoundAndVoiceHigh->bitrate = 256000;
$audioStreamConfigSoundAndVoiceHigh->defaultStreamId = 1;

$audioStreamConfigSoundAndVoiceLow = new AudioStreamConfig();
$audioStreamConfigSoundAndVoiceLow->bitrate = 128000;
$audioStreamConfigSoundAndVoiceLow->defaultStreamId = 1;

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'Multi Audio Stream Profile';
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundAndVoiceHigh;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundAndVoiceLow;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundHigh;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundLow;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$audioMetaDataJustSound = new AudioMetaData();
$audioMetaDataJustSound->defaultStreamId = 0;
$audioMetaDataJustSound->label = 'Just Sound';
$audioMetaDataJustSound->language = 'de';

$audioMetaDataSoundAndVoice = new AudioMetaData();
$audioMetaDataSoundAndVoice->defaultStreamId = 1;
$audioMetaDataSoundAndVoice->label = 'Sound and Voice';
$audioMetaDataSoundAndVoice->language = 'en';

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;
$jobConfig->speed = JobSpeedTypes::STANDARD;
$jobConfig->audioMetaData[] = $audioMetaDataJustSound;
$jobConfig->audioMetaData[] = $audioMetaDataSoundAndVoice;


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
