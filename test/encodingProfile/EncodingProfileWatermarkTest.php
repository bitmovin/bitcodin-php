<?php

    namespace test\encodingprofile;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use bitcodin\WatermarkConfig;
    use test\input\AbstractEncodingProfileTest;

    class EncodingProfileWatermarkTest extends AbstractEncodingProfileTest
    {

        public function setUp()
        {
            parent::setUp();
        }

        /**
         * @test
         * @dataProvider encodingProfileWithWatermarkConfigProvider
         * @expectedException \bitcodin\exceptions\BitcodinException
         *
         * @param EncodingProfileConfig $encodingProfileConfig
         */
        public function createEncodingProfileWithInvalidWatermarkConfig(EncodingProfileConfig $encodingProfileConfig)
        {
            EncodingProfile::create($encodingProfileConfig);
        }

        public function encodingProfileWithWatermarkConfigProvider()
        {
            $encodingProfileConfigs = array();
            $croppingConfigs = array(
                'watermarkWithNegativeTop'    => new WatermarkConfig(-10),
                'watermarkWithNegativeRight'  => new WatermarkConfig(NULL, NULL, NULL, -1),
                'watermarkWithNegativeLeft'   => new WatermarkConfig(NULL, NULL, -10, NULL),
                'watermarkWithNegativeBottom' => new WatermarkConfig(NULL, -2, NULL, NULL),
                'watermarkWithTopAndBottom'   => new WatermarkConfig(10, 2, NULL, NULL),
                'watermarkWithNoImage'        => new WatermarkConfig(10, NULL, 2, NULL)
            );

            foreach ($croppingConfigs as $name => $watermarkConfig) {
                $encodingProfileConfig = self::encodingProfileProvider($name)['defaultEncodingProfileConfig'][0];
                $encodingProfileConfig->watermarkConfig = $watermarkConfig;

                $encodingProfileConfigs[$name] = array( $encodingProfileConfig );
            }

            return $encodingProfileConfigs;
        }

    }