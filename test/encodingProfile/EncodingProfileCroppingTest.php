<?php

    namespace test\encodingprofile;

    require_once __DIR__ . '/../../vendor/autoload.php';


    use bitcodin\AudioStreamConfig;
    use bitcodin\CroppingConfig;
    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use bitcodin\VideoStreamConfig;
    use test\BitcodinApiTestBaseClass;


    class EncodingProfileCroppingTest extends BitcodinApiTestBaseClass
    {

        public function setUp()
        {
            parent::setUp();
        }

        /**
         * @test
         * @dataProvider encodingProfileProvider
         * @expectedException               \bitcodin\exceptions\BitcodinException
         */
        public function createEncodingProfileWithInvalidCroppingConfig(EncodingProfileConfig $encodingProfileConfig)
        {
            EncodingProfile::create($encodingProfileConfig);
        }

        public function encodingProfileProvider()
        {
            $encodingProfileConfigs = array();
            $croppingConfigs = array(
                'croppingWithNegativeTop'   => new CroppingConfig(-10, 2, 10, 0),
                'croppingWithNegativeRight' => new CroppingConfig(10, 2, 10, -1),
                'croppingWithNegativeLeft'  => new CroppingConfig(10, 2, -10, 1)
            );

            foreach ($croppingConfigs as $name => $croppingConfig) {
                $encodingProfileConfig = $this->getEncodingProfileConfig();
                $encodingProfileConfig->croppingConfig = $croppingConfig;

                $encodingProfileConfigs[$name] = array( $encodingProfileConfig );
            }

            return $encodingProfileConfigs;
        }

        private function getEncodingProfileConfig()
        {
            /* CREATE VIDEO STREAM CONFIG */
            $videoStreamConfig = new VideoStreamConfig();
            $videoStreamConfig->bitrate = 1024000;
            $videoStreamConfig->height = 202;
            $videoStreamConfig->width = 480;

            /* CREATE AUDIO STREAM CONFIGS */
            $audioStreamConfig = new AudioStreamConfig();
            $audioStreamConfig->bitrate = 256000;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = $this->getName() . 'EncodingProfile';
            $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

            return $encodingProfileConfig;
        }


    }
