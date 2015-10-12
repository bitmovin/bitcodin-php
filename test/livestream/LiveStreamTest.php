<?php

namespace test\livestream;
require_once __DIR__ . '/../../vendor/autoload.php';

use bitcodin\Bitcodin;
use bitcodin\EncodingProfile;
use bitcodin\GcsOutputConfig;
use bitcodin\LiveStream;
use bitcodin\Output;
use test\BitcodinApiTestBaseClass;


class LiveStreamTest extends BitcodinApiTestBaseClass
{
    /**
     * @test
     */
    public function createAndDeleteLiveInstance()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $encodingProfiles = EncodingProfile::getListAll();
        $this->assertGreaterThan(0, count($encodingProfiles));

        $gcsOutputConfig = $this::getKey("gcsOutput");

        $outputConfig = new GcsOutputConfig();
        $outputConfig->accessKey = $gcsOutputConfig->accessKey;
        $outputConfig->secretKey = $gcsOutputConfig->secretKey;
        $outputConfig->bucket = $gcsOutputConfig->bucket;
        $outputConfig->prefix = "bitcodin-php";
        $outputConfig->makePublic = true;

        $output = Output::create($outputConfig);
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