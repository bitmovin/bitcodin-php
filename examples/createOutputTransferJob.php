<?php
/**
 * Created by PhpStorm.
 * User: cwioro
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

require_once __DIR__.'/../vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('apiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://bitbucketireland.s3.amazonaws.com/Sintel-original-short.mkv';
$input = Input::create($inputConfig);

/* CREATE VIDEO STREAM CONFIG */
$videoStreamConfig1 = new VideoStreamConfig();
$videoStreamConfig1->bitrate = 400000;
$videoStreamConfig1->height = 240;
$videoStreamConfig1->width = 426;

$videoStreamConfig2 = new VideoStreamConfig();
$videoStreamConfig2->bitrate = 800000;
$videoStreamConfig2->height = 360;
$videoStreamConfig2->width = 640;

$videoStreamConfig3 = new VideoStreamConfig();
$videoStreamConfig3->bitrate = 1200000;
$videoStreamConfig3->height = 480;
$videoStreamConfig3->width = 854;

$videoStreamConfig4 = new VideoStreamConfig();
$videoStreamConfig4->bitrate = 2400000;
$videoStreamConfig4->height = 720;
$videoStreamConfig4->width = 1280;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig = new AudioStreamConfig();
$audioStreamConfig->bitrate = 128000;

/* CREATE ENCODING PROFILE */
$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'HD Profile';
$encodingProfileConfig->videoStreamConfigs = [$videoStreamConfig1, $videoStreamConfig2, $videoStreamConfig3, $videoStreamConfig4];
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

/* CREATE OUTPUT */

$outputConfig = new FtpOutputConfig();
$outputConfig->name = "123";
$outputConfig->host = str_replace('ftp://', '', getKey('ftpServer'));
$outputConfig->username = getKey('ftpUser');
$outputConfig->password = getKey('ftpPassword');

$output = Output::create($outputConfig);

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
    echo 'Job: ' . $job->jobId . ' Status[' . $job->status . "]\n";
    sleep(1);
} while($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);


/* WAIT TIL TRANSFER IS FINISHED */
do{
    $transfers = $job->getTransfers();
    $finishedTransfer = 0;
    foreach($transfers as $transfer)
    {
        echo 'Transfer: JobID ' . $transfer->id . ' Progress[' . $transfer->progress . "]\n";
        if($transfer->progress == 100)
            $finishedTransfer++;
    }
    sleep(1);
} while($finishedTransfer < sizeof($transfers));

/* HELPER FUNCTION */
function getKey($key)
{
    return json_decode(file_get_contents(__DIR__.'/../test/config.json'))->{$key};
}