<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 01.07.15
 * Time: 15:31
 */


use bitcodin\AudioMetaData;
use bitcodin\VideoMetaData;
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
$inputConfig->url = 'http://path/to/your/closed/captions/file.mp4';
$input = Input::create($inputConfig);

/* CREATE VIDEO STREAM CONFIG */
$videoStreamConfig = new VideoStreamConfig();
$videoStreamConfig->bitrate = 1024000;
$videoStreamConfig->height = 480;
$videoStreamConfig->width = 202;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfigSoundHigh = new AudioStreamConfig();
$audioStreamConfigSoundHigh->bitrate = 256000;
$audioStreamConfigSoundHigh->defaultStreamId = 0;

$spanishAudio = new AudioStreamConfig();
$spanishAudio->bitrate = 44100;
$spanishAudio->defaultStreamId = 0;

$englishAudio = new AudioStreamConfig();
$englishAudio->bitrate = 44100;
$englishAudio->defaultStreamId= 1;

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'Multi Audio Closed Captions Profile';
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
$encodingProfileConfig->audioStreamConfigs[] = $spanishAudio;
$encodingProfileConfig->audioStreamConfigs[] = $englishAudio;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$videoMeta = new VideoMetaData();
$videoMeta->label = 'Spanish Subtitle';
$videoMeta->language = 'es';
$videoMeta->defaultStreamId = 0;

$englishAudioMeta = new AudioMetaData();
$englishAudioMeta->defaultStreamId = 0;
$englishAudioMeta->label = 'English';
$englishAudioMeta->language = 'en';

$audioMetaDataSoundAndVoice = new AudioMetaData();
$audioMetaDataSoundAndVoice->defaultStreamId = 1;
$audioMetaDataSoundAndVoice->label = 'Spanish';
$audioMetaDataSoundAndVoice->language = 'es';

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;
$jobConfig->speed = JobSpeedTypes::STANDARD;
$jobConfig->audioMetaData[] = $audioMetaDataJustSound;
$jobConfig->audioMetaData[] = $audioMetaDataSoundAndVoice;
$jobConfig->extractClosedCaptions = true;


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