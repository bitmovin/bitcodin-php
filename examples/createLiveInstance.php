<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 31.08.15
 * Time: 15:28
 */

use bitcodin\Bitcodin;
use bitcodin\LiveInstance;

require_once __DIR__.'/../vendor/autoload.php';

Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$liveInstance = LiveInstance::create("live stream test");

echo "Waiting until live stream is RUNNING...\n";
while($liveInstance->status != "RUNNING")
{
    sleep(2);
    $liveInstance->update();
    if($liveInstance->status == "ERROR")
    {
        echo "ERROR occurred!";
        throw new \Exception("Error occured during Live stream creation");
    }
}

echo "Livestream RTMP push URL: ".$liveInstance->rtmp_push_url."\n";
echo "MPD URL: ".$liveInstance->mpd_url."\n";
echo "HLS URL: ".$liveInstance->hls_url."\n";

$liveInstance = LiveInstance::delete($liveInstance->id);

echo "Waiting until live stream is TERMINATED...\n";
while($liveInstance->status != "TERMINATED")
{
    sleep(2);
    $liveInstance->update();
    if($liveInstance->status == "ERROR")
    {
        echo "ERROR occurred!";
        throw new \Exception("Error occured during Live stream deletion");
    }
}

echo "Live stream TERMINATED\n";