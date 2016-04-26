<?php

use bitcodin\Bitcodin;
use bitcodin\Thumbnail;
use bitcodin\ThumbnailConfig;

require_once __DIR__.'/../vendor/autoload.php';

Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$thumbnailConfig = new ThumbnailConfig();
$thumbnailConfig->jobId = 1234; // insert your jobID
$thumbnailConfig->height = 320;
$thumbnailConfig->position = 5;
$thumbnailConfig->async = true;

$thumbnail = Thumbnail::create($thumbnailConfig);
echo "Thumbnail ID: " . $thumbnail->id . "\n";
echo "Thumbnail URL: " . $thumbnail->thumbnailUrl . "\n";
echo "Thumbnail State: " . $thumbnail->state . "\n";

while ($thumbnail->state != 'FINISHED' && $thumbnail->state != 'ERROR')
{
    sleep(1);
    $thumbnail = Thumbnail::get($thumbnail->id);
    echo "Thumbnail ID: " . $thumbnail->id . "\n";
    echo "Thumbnail URL: " . $thumbnail->thumbnailUrl . "\n";
    echo "Thumbnail State: " . $thumbnail->state . "\n";
}
