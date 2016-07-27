<?php

    require_once __DIR__ . '/../vendor/autoload.php';

    \bitcodin\Bitcodin::setApiToken('YOUR API KEY'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

    $namingPostfix = date("YmdHis");

    /* CREATE ENCODING PROFILE FOR YOUR LIVE STREAM */
    $encodingProfileConfig = new \bitcodin\EncodingProfileConfig();
    $encodingProfileConfig->name = 'FullHD+HD+SD Live Stream SL1';
    $encodingProfileConfig->segmentLength = 1; //Custom segment length in seconds

    /* CREATE VIDEO STREAM CONFIGS */
    $videoStreamConfig = new \bitcodin\VideoStreamConfig();
    $videoStreamConfig->bitrate = 4800000;
    $videoStreamConfig->width = 1920;
    $videoStreamConfig->height = 1080;

    $videoStreamConfig = new \bitcodin\VideoStreamConfig();
    $videoStreamConfig->bitrate = 2400000;
    $videoStreamConfig->width = 1280;
    $videoStreamConfig->height = 720;

    $videoStreamConfig = new \bitcodin\VideoStreamConfig();
    $videoStreamConfig->bitrate = 1200000;
    $videoStreamConfig->width = 854;
    $videoStreamConfig->height = 480;

    /* CREATE AUDIO STREAM CONFIGS */
    $audio = new \bitcodin\AudioStreamConfig();
    $audio->bitrate = 128000;

    $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
    $encodingProfileConfig->audioStreamConfigs[] = $audio;

    /* CREATE ENCODING PROFILE */
    $encodingProfile = \bitcodin\EncodingProfile::create($encodingProfileConfig);

    /* CREATE OUTPUT */
    $outputConfig = new \bitcodin\S3OutputConfig();
    $outputConfig->name = "Livestream Output Config";
    $outputConfig->accessKey = "YOUR ACCESS KEY";
    $outputConfig->secretKey = "YOUR SECRET KEY";
    $outputConfig->bucket = "YOUR BUCKETNAME";
    $outputConfig->prefix = "livestream" . $namingPostfix;
    $outputConfig->makePublic = true;

    $output = \bitcodin\Output::create($outputConfig);

    /* CREATE LIVE STREAM */
    $livestreamName = "livestream-test-" . $namingPostfix;
    $livestreamStreamKey = "livestreamtestone";
    $timeShift = 30;
    $liveEdgeOffset = 10;
    $liveInstance = \bitcodin\LiveStream::create($livestreamName, $livestreamStreamKey, $encodingProfile, $output, $timeShift, $liveEdgeOffset);

    echo "Waiting until live stream is RUNNING...\n";
    while ($liveInstance->status != $liveInstance::STATUS_RUNNING) {
        sleep(2);
        $liveInstance->update();
        if ($liveInstance->status == $liveInstance::STATUS_ERROR) {
            echo "ERROR occurred!";
            throw new \Exception("Error occurred during Live stream creation");
        }
    }

    echo "Livestream RTMP push URL: " . $liveInstance->rtmpPushUrl . "\n";
    echo "Stream Key: " . $liveInstance->streamKey . "\n";
    echo "MPD URL: " . $liveInstance->mpdUrl . "\n";
    echo "HLS URL: " . $liveInstance->hlsUrl . "\n";

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
