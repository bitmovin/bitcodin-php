<?php

    namespace test\input;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\AudioStreamConfig;
    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use bitcodin\exceptions\BitcodinResourceNotFoundException;
    use bitcodin\VideoStreamConfig;
    use test\BitcodinApiTestBaseClass;

    abstract class AbstractEncodingProfileTest extends BitcodinApiTestBaseClass
    {

        public function setUp()
        {
            parent::setUp();
        }

        protected function getEncodingProfile(EncodingProfile $encodingProfile)
        {
            $fetchedEncodingProfile = EncodingProfile::get($encodingProfile->encodingProfileId);
            $this->checkEncodingProfile($fetchedEncodingProfile);

            return $fetchedEncodingProfile;
        }

        protected function deleteEncodingProfile(EncodingProfile $encodingProfile)
        {
            $this->setExpectedException(BitcodinResourceNotFoundException::class);

            $encodingProfile->delete($encodingProfile->encodingProfileId);
            $this->getEncodingProfile($encodingProfile);
        }

        protected function listEncodingProfile()
        {
            $this->markTestIncomplete("incomplete");

            foreach (EncodingProfile::getListAll() as $output) {
                $this->checkEncodingProfile($output);
            }
        }

        protected function checkEncodingProfile(EncodingProfile $encodingProfile)
        {
            $this->assertInstanceOf(EncodingProfile::class, $encodingProfile);
            $this->assertTrue(is_numeric($encodingProfile->encodingProfileId), 'encodingProfileId not set');
            $this->assertTrue(is_array($encodingProfile->videoStreamConfigs), 'videoStreamConfigs not set');
            $this->assertTrue(is_array($encodingProfile->audioStreamConfigs), 'audioStreamConfigs not set');
        }

        public static function encodingProfileProvider($customName = "Default")
        {
            /* CREATE VIDEO STREAM CONFIG */
            $videoStreamConfig = new VideoStreamConfig();
            $videoStreamConfig->bitrate = 1024000;
            $videoStreamConfig->width = 480;
            $videoStreamConfig->height = 202;

            /* CREATE AUDIO STREAM CONFIGS */
            $audioStreamConfig = new AudioStreamConfig();
            $audioStreamConfig->bitrate = 256000;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = $customName . ' EncodingProfile';
            $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

            return array(
                'defaultEncodingProfileConfig' => array( $encodingProfileConfig )
            );
        }
    }
