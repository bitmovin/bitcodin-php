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

class LiveInstanceTest extends AbstractLiveInstanceTest
{
    /**
     * @test
     */
    public function createAndDeleteLiveInstance()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $liveInstance = LiveInstance::create("testliveinstance");

        while($liveInstance->status != LiveInstance::STATUS_RUNNING)
        {
            sleep(2);
            $liveInstance->update();
            if($liveInstance->status == LiveInstance::STATUS_ERROR)
            {
                throw new \Exception("Error occurred during Live stream creation");
            }
        }

        $this->assertNotEquals($liveInstance->status, LiveInstance::STATUS_ERROR);
        $this->assertInstanceOf('bitcodin\LiveInstance', $liveInstance);
        $this->assertNotNull($liveInstance->id);

        $liveInstance = LiveInstance::delete($liveInstance->id);

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

        $this->assertNotEquals($liveInstance->status, LiveInstance::STATUS_ERROR);
        $this->assertNotNull($liveInstance->terminated_at);
    }
}