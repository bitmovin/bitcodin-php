<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 31.08.15
 * Time: 15:45
 */

namespace test\liveInstance;
require_once __DIR__ . '/../../vendor/autoload.php';

use bitcodin\Bitcodin;
use bitcodin\EncodingProfile;
use bitcodin\LiveStream;
use bitcodin\Output;

class LiveStreamTest extends AbstractLiveStreamTest
{
    /**
     * @test
     */
    public function createAndDeleteLiveInstance()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $encodingProfiles = EncodingProfile::getListAll();
        $this->assertGreaterThan(0, count($encodingProfiles));

        $outputs = Output::getListAll();
        $this->assertGreaterThan(0, count($outputs));

        $output = null;
        foreach($outputs as $o)
        {
            if($o->type == "gcs")
                $output = $o;
        }

        $this->assertNotNull($output);

        $liveInstance = LiveStream::create("testliveinstance", "stream", $encodingProfiles[0], $output, 30);

        while($liveInstance->status != LiveStream::STATUS_RUNNING)
        {
            sleep(2);
            $liveInstance->update();
            if($liveInstance->status == LiveStream::STATUS_ERROR)
            {
                throw new \Exception("Error occurred during Live stream creation");
            }
        }

        $this->assertNotEquals($liveInstance->status, LiveStream::STATUS_ERROR);
        $this->assertInstanceOf('bitcodin\LiveInstance', $liveInstance);
        $this->assertNotNull($liveInstance->id);

        $liveInstance = LiveStream::delete($liveInstance->id);

        echo "Waiting until live stream is TERMINATED...\n";
        while($liveInstance->status != "TERMINATED")
        {
            sleep(2);
            $liveInstance->update();
            if($liveInstance->status == "ERROR")
            {
                echo "ERROR occurred!";
                throw new \Exception("Error occurred during Live stream deletion");
            }
        }

        $this->assertNotEquals($liveInstance->status, LiveStream::STATUS_ERROR);
        $this->assertNotNull($liveInstance->terminatedAt);
    }
}