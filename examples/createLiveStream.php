<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 31.08.15
 * Time: 15:28
 */

use bitcodin\AudioStreamConfig;
use bitcodin\Bitcodin;
use bitcodin\EncodingProfileConfig;
use bitcodin\GcsOutputConfig;
use bitcodin\HttpInputConfig;
use bitcodin\Input;
use bitcodin\LiveStream;
use bitcodin\EncodingProfile;
use bitcodin\Output;
use bitcodin\VideoStreamConfig;

require_once __DIR__.'/../vendor/autoload.php';

Bitcodin::setApiToken('YOUR API KEY'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';
$input = Input::create($inputConfig);

/* CREATE ENCODING PROFILE FOR YOUR LIVE STREAM */
$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'Live Stream Profile';

/* CREATE VIDEO STREAM CONFIGS */
$low = new VideoStreamConfig();
$low->bitrate = 1000000;
$low->height = 480;
$low->width = 854;

$encodingProfileConfig->videoStreamConfigs[] = $low;

$medium = new VideoStreamConfig();
$medium->bitrate = 1500000;
$medium->height = 720;
$medium->width = 1280;

$encodingProfileConfig->videoStreamConfigs[] = $medium;

$high = new VideoStreamConfig();
$high->bitrate = 3000000;
$high->height = 1080;
$high->width = 1920;

$encodingProfileConfig->videoStreamConfigs[] = $high;


/* CREATE AUDIO STREAM CONFIGS */
$audio = new AudioStreamConfig();
$audio->bitrate = 128000;

$encodingProfileConfig->audioStreamConfigs[] = $audio;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

/* CREATE OUTPUT */
$outputConfig = new GcsOutputConfig();
$outputConfig->name = "Livestream Output Config";
$outputConfig->accessKey = "YOUR GCS ACCESS KEY";
$outputConfig->secretKey = "YOUR GCS SECRET KEY";
$outputConfig->bucket = "YOUR GCS BUCKET";
$outputConfig->prefix = "livestream".date("YmdHis");
$outputConfig->makePublic = true;

$output = Output::create($outputConfig);


/* CREATE LIVE STREAM */
$liveInstance = LiveStream::create("live-stream-test", "stream", $encodingProfile, $output, 30);

echo "Waiting until live stream is RUNNING...\n";
while($liveInstance->status != $liveInstance::STATUS_RUNNING)
{
    sleep(2);
    $liveInstance->update();
    if($liveInstance->status == $liveInstance::STATUS_ERROR)
    {
        echo "ERROR occurred!";
        throw new \Exception("Error occurred during Live stream creation");
    }
}

echo "Livestream RTMP push URL: ".$liveInstance->rtmpPushUrl."\n";
echo "Stream Key: ".$liveInstance->streamKey."\n";
echo "MPD URL: ".$liveInstance->mpdUrl."\n";
echo "HLS URL: ".$liveInstance->hlsUrl."\n";

/*
 * **************************************************
 * AT THIS POINT YOU CAN STREAM TO YOUR LIVE STREAM *
 * **************************************************
 */

/*
 * *********************************************************************************
 * IMPORTANT! IF YOU HAVE FINISHED STREAMING DON'T FORGET TO DELETE YOUR LIVE STREAM
 * *********************************************************************************
 *
 * Use something like the following to delete:
 *

    LiveStream::delete($liveInstance->id);

    echo "Waiting until live stream is TERMINATED...\n";
    while($liveInstance->status != $liveInstance::STATUS_TERMINATED)
    {
        sleep(2);
        $liveInstance->update();
        if($liveInstance->status == $liveInstance::STATUS_ERROR)
        {
            echo "ERROR occurred!";
            throw new \Exception("Error occurred during Live stream deletion");
        }
    }

    echo "Live stream TERMINATED\n";
 */