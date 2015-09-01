<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 31.08.15
 * Time: 15:45
 */

namespace test\liveInstance;
require_once __DIR__ . '/../../vendor/autoload.php';

use bitcodin\Bitcodin;
use bitcodin\LiveInstance;

Bitcodin::setApiToken('de93f6610ef1e8f5389b038f74946e2d0bda5d79f54d2e36b6af57618e696681');

class LiveInstanceTest extends AbstractLiveInstanceTest
{
    /**
     * @test
     */
    public function createAndDeleteLiveInstance()
    {
        $liveInstance = LiveInstance::create("testliveinstance");

        $first  = new \DateTime();
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
        $second = new \DateTime();
        $diff = $first->diff( $second );
        echo "Time elapsed until RUNNING: ".$diff->format( '%H:%I:%S' )."\n";

        echo "Livestream RTMP push URL: ".$liveInstance->rtmp_push_url."\n";
        echo "MPD URL: ".$liveInstance->mpd_url."\n";
        echo "HLS URL: ".$liveInstance->hls_url."\n";


        $liveInstance = LiveInstance::delete($liveInstance->id);

        $first  = new \DateTime();
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
        $second = new \DateTime();
        $diff = $first->diff( $second );
        echo "Time elapsed until TERMINATED: ".$diff->format( '%H:%I:%S' )."\n";

    }
}

$test = new LiveInstanceTest();
$test->createAndDeleteLiveInstance();