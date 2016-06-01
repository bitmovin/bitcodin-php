<?php

use bitcodin\Bitcodin;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\EncodingProfile;
use bitcodin\EncodingProfileConfig;
use bitcodin\MVPredictionMode;

require_once __DIR__.'/../vendor/autoload.php';

// Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API
Bitcodin::setApiToken('insertYourApiKey');

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'Custom Encoder Options';

$videoStreamConfig1 = new VideoStreamConfig();
$videoStreamConfig1->bitrate = 4800000;
$videoStreamConfig1->height = 1080;
$videoStreamConfig1->width = 1920;
$videoStreamConfig1->qpMax = 40;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig1;

$videoStreamConfig2 = new VideoStreamConfig();
$videoStreamConfig2->bitrate = 2400000;
$videoStreamConfig2->height = 720;
$videoStreamConfig2->width = 1280;
$videoStreamConfig2->qpMax = 45;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig2;

$videoStreamConfig3 = new VideoStreamConfig();
$videoStreamConfig3->bitrate = 1200000;
$videoStreamConfig3->height = 480;
$videoStreamConfig3->width = 854;
$videoStreamConfig3->bFrames = 0;
$videoStreamConfig3->refFrames = 2;
$videoStreamConfig3->qpMin = 20;
$videoStreamConfig3->mvPredictionMode = MVPredictionMode::SPATIAL;
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig3;

$audioStreamConfig = new AudioStreamConfig();
$audioStreamConfig->bitrate = 128000;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

$encodingProfile = EncodingProfile::create($encodingProfileConfig);

