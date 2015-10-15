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
        $outputConfig->name = "bitcodin-php GCS Output";
        $outputConfig->prefix = "bitcodin-php";
        $outputConfig->makePublic = true;

        $output = Output::create($outputConfig);
        $this->assertNotNull($output);

        $liveStream = LiveStream::create("testliveinstance", "stream", $encodingProfiles[0], $output, 30);
        $this->assertNotNull($liveStream->id);

        while($liveStream->status != LiveStream::STATUS_RUNNING)
        {
            sleep(2);
            $liveStream->update();
            if($liveStream->status == LiveStream::STATUS_ERROR)
            {
                throw new \Exception("Error occurred during Live stream creation");
            }
        }

        $this->assertNotEquals($liveStream->status, LiveStream::STATUS_ERROR);
        $this->assertNotNull($liveStream->id);

        LiveStream::delete($liveStream->id);

        echo "Waiting until live stream is TERMINATED...\n";
        while($liveStream->status != "TERMINATED")
        {
            sleep(2);
            $liveStream->update();
            if($liveStream->status == "ERROR")
            {
                echo "ERROR occurred!";
                throw new \Exception("Error occurred during Live stream deletion");
            }
        }

        $this->assertNotEquals($liveStream->status, LiveStream::STATUS_ERROR);
        $this->assertEquals($liveStream->status, LiveStream::STATUS_TERMINATED);
        $this->assertNotNull($liveStream->terminatedAt);
    }
}