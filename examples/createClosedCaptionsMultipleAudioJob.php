<?php

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
$videoStreamConfig->bitrate = 512000;
$videoStreamConfig->height = 202;
$videoStreamConfig->width = 480;

/* CREATE AUDIO STREAM CONFIGS */
$spanishAudio = new AudioStreamConfig();
$spanishAudio->bitrate = 128000;
$spanishAudio->defaultStreamId = 0;

$englishAudio = new AudioStreamConfig();
$englishAudio->bitrate = 128000;
$englishAudio->defaultStreamId= 1;

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'Multi Audio Closed Captions Profile';
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
$encodingProfileConfig->audioStreamConfigs[] = $spanishAudio;
$encodingProfileConfig->audioStreamConfigs[] = $englishAudio;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$videoMeta = new VideoMetaData();
$videoMeta->label = 'Spanish';
$videoMeta->language = 'es';
$videoMeta->defaultStreamId = 0;

$englishAudioMeta = new AudioMetaData();
$englishAudioMeta->defaultStreamId = 0;
$englishAudioMeta->label = 'English';
$englishAudioMeta->language = 'en';

$spanishAudioMeta = new AudioMetaData();
$spanishAudioMeta->defaultStreamId = 1;
$spanishAudioMeta->label = 'Spanish';
$spanishAudioMeta->language = 'es';

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;
$jobConfig->speed = JobSpeedTypes::STANDARD;
$jobConfig->videoMetaData[] = $videoMeta;
$jobConfig->audioMetaData[] = $englishAudioMeta;
$jobConfig->audioMetaData[] = $spanishAudioMeta;
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
