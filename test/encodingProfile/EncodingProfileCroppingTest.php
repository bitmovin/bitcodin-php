<?php

    namespace test\encodingprofile;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\CroppingConfig;
    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use test\input\AbstractEncodingProfileTest;

    class EncodingProfileCroppingTest extends AbstractEncodingProfileTest
    {

        public function setUp()
        {
            parent::setUp();
        }

        /**
         * @test
         * @dataProvider encodingProfileWithCroppingConfigProvider
         * @expectedException \bitcodin\exceptions\BitcodinException
         *
         * @param EncodingProfileConfig $encodingProfileConfig
         */
        public function createEncodingProfileWithInvalidCroppingConfig(EncodingProfileConfig $encodingProfileConfig)
        {
            EncodingProfile::create($encodingProfileConfig);
        }

        public function encodingProfileWithCroppingConfigProvider()
        {
            $encodingProfileConfigs = array();
            $croppingConfigs = array(
                'croppingWithNegativeTop'   => new CroppingConfig(-10, 2, 10, 0),
                'croppingWithNegativeRight' => new CroppingConfig(10, 2, 10, -1),
                'croppingWithNegativeLeft'  => new CroppingConfig(10, 2, -10, 1)
            );

            foreach ($croppingConfigs as $name => $croppingConfig) {
                $encodingProfileConfig = self::encodingProfileProvider($name)['defaultEncodingProfileConfig'][0];
                $encodingProfileConfig->croppingConfig = $croppingConfig;

                $encodingProfileConfigs[$name] = array( $encodingProfileConfig );
            }

            return $encodingProfileConfigs;
        }
    }
