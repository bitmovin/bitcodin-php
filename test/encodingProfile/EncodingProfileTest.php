<?php

    namespace test\encodingprofile;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use test\input\AbstractEncodingProfileTest;

    class EncodingProfileTest extends AbstractEncodingProfileTest
    {

        public function setUp()
        {
            parent::setUp();
        }

        /**
         * @test
         *
         * @return EncodingProfile
         */
        public function create()
        {
            $encodingProfileConfig = $this->encodingProfileProvider()['defaultEncodingProfileConfig'][0];
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);
            $this->checkEncodingProfile($encodingProfile);

            return $encodingProfile;
        }

        /**
         * @test
         * @expectedException \bitcodin\exceptions\BitcodinException
         */
        public function createWithoutAnyStreamConfigs()
        {
            $encodingProfileConfig = new EncodingProfileConfig();
            EncodingProfile::create($encodingProfileConfig);
        }

        /**
         * @test
         * @depends create
         *
         * @param EncodingProfile $encodingProfile
         *
         * @return EncodingProfile|mixed
         */
        public function get(EncodingProfile $encodingProfile)
        {
            return $this->getEncodingProfile($encodingProfile);
        }

        /**
         * @test
         */
        public function listAll()
        {
            $this->markTestSkipped("needs to be refactored");
            $this->listEncodingProfile();
        }
    }
