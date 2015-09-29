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
Bitcodin::setApiToken('566a1bc718ecd0deacbea17d4cb2cf9fdf3a57cc19498e90a06c77d44d28104d'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://bitbucketireland.s3.amazonaws.com/Sintel-original-short.mkv';
$input = Input::create($inputConfig);
echo "Input successfully created! \n";
echo json_encode($input)."\n";

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
$encodingProfileConfig->name = 'ftp Test Profile '.time();
$encodingProfileConfig->videoStreamConfigs = [$videoStreamConfig1, $videoStreamConfig2, $videoStreamConfig3, $videoStreamConfig4];
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;
$encodingProfile = EncodingProfile::create($encodingProfileConfig);
echo "Encoding-Profile successfully created! \n";
echo json_encode($encodingProfile)."\n";

/* CREATE OUTPUT */

$outputConfig = new FtpOutputConfig();
$outputConfig->name = "FTP Campus Test ".time();
$outputConfig->host = getKey('ftpServer');
$outputConfig->username = getKey('ftpUser');
$outputConfig->password = getKey('ftpPassword');

$output = Output::create($outputConfig);
echo "Output successfully created! \n";
echo json_encode($output)."\n";

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->output = $output;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;

/* CREATE JOB */
$job = Job::create($jobConfig);

echo "\n\nCreate Encoding...\n\n";

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    echo "\r" . date_create()->format('d.m.Y H:i:s') . ' - Job: ' . $job->jobId . ' Status[' . $job->status . "]";
    sleep(2);
} while($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);

echo "\n\nWait for Transfer...\n\n";

/* WAIT TIL TRANSFER IS FINISHED */
do {
    $date = "\r" . date_create()->format('d.m.Y H:i:s');
    try {
        $transfers = $job->getTransfers();
        $finishedTransfer = 0;
        foreach($transfers as $transfer)
        {
            echo $date . ' - Transfer: JobID ' . $transfer->id . ' Progress[' . $transfer->progress . "] Status[".$transfer->status."]";
            if($transfer->progress == 100)
                $finishedTransfer++;
        }
        sleep(2);
    } catch (\bitcodin\exceptions\BitcodinResourceNotFoundException $e) {
        echo $date . " - Transfer: Waiting for Transfer...\n";
        $transfers = array();
        sleep(2);
    } catch (\Exception $e) {
        echo "Unexpected Error\n";
    }
} while(empty($transfers) || $finishedTransfer < count($transfers));

echo "\n\nTransfer finished...\n\n";

var_dump($transfers);

/* HELPER FUNCTION */
function getKey($key)
{
    return json_decode(file_get_contents(__DIR__.'/../test/config.json'))->{$key};
}