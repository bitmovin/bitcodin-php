<?php

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

require_once __DIR__.'/../vendor/autoload.php';

function getHeight($width, $aspectRatio)
{
    $height = intval(round(($width / $aspectRatio), 0));
    if ($height % 2 != 0)
    {
        $height++;
    }

    return $height;
}

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourBitcodinApiKeyHere'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig                  = new HttpInputConfig();
$inputConfig->url             = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';
$input                        = Input::create($inputConfig);

$inputVideoMediaConfiguration = NULL;

foreach ($input->getVideoMediaConfigurations() as $inputMediaConfiguration)
{
    if ($inputMediaConfiguration->type === 'video')
    {
        $inputVideoMediaConfiguration = $inputMediaConfiguration;
        break;
    }
}

if (is_null($inputVideoMediaConfiguration))
{
    throw new \Exception("No video media configuration found!");
}

$inputWidth                   = $inputVideoMediaConfiguration->width;
$inputHeight                  = $inputVideoMediaConfiguration->height;
$aspectRatio                  = $inputWidth / $inputHeight;

// Predefined widths
$widths                       = array(426, 640, 854, 1280, 1920);
$bitrates                     = array(400*1000, 800*1000, 1200*1000, 2400*1000, 4800*1000);
$heights                      = array();

foreach ($widths as $width)
{
    array_push($heights, getHeight($width, $aspectRatio));
}

$videoStreamConfigurations    = array();

for ($i = 0; $i < count($widths); $i++)
{
    $videoStreamConfiguration = new VideoStreamConfig();
    $videoStreamConfiguration->width   = $widths[$i];
    $videoStreamConfiguration->height  = $heights[$i];
    $videoStreamConfiguration->bitrate = $bitrates[$i];

    array_push($videoStreamConfigurations, $videoStreamConfiguration);
}

$audioStreamConfig = new AudioStreamConfig();
$audioStreamConfig->bitrate = 128000;

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'MyApiTestEncodingProfile';
$encodingProfileConfig->videoStreamConfigs = $videoStreamConfigurations;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;

$job = Job::create($jobConfig);
