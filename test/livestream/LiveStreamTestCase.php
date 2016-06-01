<?php

    namespace test\livestream;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\EncodingProfile;
    use bitcodin\GcsOutputConfig;
    use bitcodin\LiveStream;
    use bitcodin\Output;
    use test\BitcodinApiTestBaseClass;


    class LiveStreamTestCase extends BitcodinApiTestBaseClass
    {

        /**
         * @test
         * @return Output
         */
        public function createLiveStreamOutput()
        {
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

            return $output;
        }

        /**
         * @test
         * @depends createLiveStreamOutput
         * @return LiveStream
         */
        public function createLiveStream(Output $output)
        {
            $encodingProfiles = EncodingProfile::getListAll();
            $this->assertGreaterThan(0, count($encodingProfiles));

            $liveStream = LiveStream::create("testliveinstance", "stream", $encodingProfiles[0], $output, 30);
            $this->assertNotNull($liveStream->id);

            return $liveStream;
        }

        /**
         * @test
         * @depends createLiveStream
         *
         * @param LiveStream $liveStream
         *
         * @return LiveStream
         * @throws \Exception
         */
        public function waitForLiveStream(LiveStream $liveStream)
        {
            while ($liveStream->status != LiveStream::STATUS_RUNNING) {
                sleep(2);
                $liveStream->update();
                if ($liveStream->status == LiveStream::STATUS_ERROR) {
                    throw new \Exception("Error occurred during Live stream creation");
                }
            }

            $this->assertNotEquals($liveStream->status, LiveStream::STATUS_ERROR);
            $this->assertNotNull($liveStream->id);

            return $liveStream;
        }

        /**
         * @test
         * @depends waitForLiveStream
         *
         * @param LiveStream $liveStream
         *
         * @throws \Exception
         */
        public function terminateLiveStream(LiveStream $liveStream)
        {
            LiveStream::delete($liveStream->id);

            echo "Waiting until live stream is TERMINATED...\n";
            while ($liveStream->status != LiveStream::STATUS_TERMINATED) {
                sleep(2);
                $liveStream->update();
                if ($liveStream->status == LiveStream::STATUS_ERROR) {
                    echo "ERROR occurred!";
                    throw new \Exception("Error occurred during Live stream deletion");
                }
            }

            $this->assertNotEquals($liveStream->status, LiveStream::STATUS_ERROR);
            $this->assertEquals($liveStream->status, LiveStream::STATUS_TERMINATED);
        }
    }