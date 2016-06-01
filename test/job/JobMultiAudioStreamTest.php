<?php

    namespace test\job;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\AudioMetaData;
    use bitcodin\AudioStreamConfig;
    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use bitcodin\HttpInputConfig;
    use bitcodin\Input;
    use bitcodin\Job;
    use bitcodin\JobConfig;
    use bitcodin\JobSpeedTypes;
    use bitcodin\ManifestTypes;
    use bitcodin\VideoStreamConfig;

    class JobMultiLanguageTest extends AbstractJobTest
    {

        const URL_FILE = 'http://bitbucketireland.s3.amazonaws.com/Sintel-two-audio-streams-short.mkv';

        /** TEST JOB CREATION */

        public function setUp()
        {
            parent::setUp();
        }

        public function testMultiLanguageJob()
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;
            $input = Input::create($inputConfig);

            $audioMetaDataJustSound = new AudioMetaData();
            $audioMetaDataJustSound->defaultStreamId = 0;
            $audioMetaDataJustSound->label = 'Just Sound';
            $audioMetaDataJustSound->language = 'de';

            $audioMetaDataSoundAndVoice = new AudioMetaData();
            $audioMetaDataSoundAndVoice->defaultStreamId = 1;
            $audioMetaDataSoundAndVoice->label = 'Sound and Voice';
            $audioMetaDataSoundAndVoice->language = 'en';

            /* CREATE ENCODING PROFILE */
            $encodingProfile = $this->getMultiLanguageEncodingProfile();

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->speed = JobSpeedTypes::STANDARD;
            $jobConfig->audioMetaData[] = $audioMetaDataJustSound;
            $jobConfig->audioMetaData[] = $audioMetaDataSoundAndVoice;

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf('bitcodin\Job', $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        public function testMultiAudioStreamJobWrongMetaDataConfig()
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;
            $input = Input::create($inputConfig);

            $audioMetaDataJustSound = new AudioMetaData();
            $audioMetaDataJustSound->defaultStreamId = 0;
            $audioMetaDataJustSound->label = 'Just Sound';
            $audioMetaDataJustSound->language = 'de';

            $audioMetaDataSoundAndVoice = new AudioMetaData();
            $audioMetaDataSoundAndVoice->defaultStreamId = 1;
            $audioMetaDataSoundAndVoice->label = 'Sound and Voice';
            $audioMetaDataSoundAndVoice->language = 'en';

            $audioMetaDataWrong = new AudioMetaData();
            $audioMetaDataWrong->defaultStreamId = 2;
            $audioMetaDataWrong->label = 'Not existing';
            $audioMetaDataWrong->language = 'ex';

            /* CREATE ENCODING PROFILE */
            $encodingProfile = $this->getMultiLanguageEncodingProfile();

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->speed = JobSpeedTypes::STANDARD;
            $jobConfig->audioMetaData[] = $audioMetaDataJustSound;
            $jobConfig->audioMetaData[] = $audioMetaDataSoundAndVoice;
            $jobConfig->audioMetaData[] = $audioMetaDataWrong;

            /* CREATE JOB */
            $this->setExpectedException('bitcodin\exceptions\BitcodinException');
            $job = Job::create($jobConfig);

            return $job;
        }

        public function testMultiLanguageJobWithMissingAudioMetaDataFields()
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;
            $input = Input::create($inputConfig);

            $encodingProfile = $this->getMultiLanguageEncodingProfile();

            $audioMetaDataJustSound = new AudioMetaData();
            $audioMetaDataJustSound->defaultStreamId = 0;
            $audioMetaDataJustSound->label = 'Just Sound';

            $audioMetaDataSoundAndVoice = new AudioMetaData();
            $audioMetaDataSoundAndVoice->defaultStreamId = 1;
            $audioMetaDataSoundAndVoice->language = 'en';

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;
            $jobConfig->speed = JobSpeedTypes::STANDARD;
            $jobConfig->audioMetaData[] = $audioMetaDataJustSound;
            $jobConfig->audioMetaData[] = $audioMetaDataSoundAndVoice;

            $this->setExpectedException('bitcodin\exceptions\BitcodinException');
            $job = Job::create($jobConfig);

            return $job;
        }

        /** TEST JOB PROGRESS*/

        /**
         * @depends testMultiLanguageJob
         */
        public function testUpdateMultiLanguageJob(Job $job)
        {
            return $this->updateJob($job);
        }

        /**
         * @return EncodingProfile
         */
        private function getMultiLanguageEncodingProfile()
        {
            /* CREATE VIDEO STREAM CONFIG */
            $videoStreamConfig = new VideoStreamConfig();
            $videoStreamConfig->bitrate = 1024000;
            $videoStreamConfig->height = 202;
            $videoStreamConfig->width = 480;

            /* CREATE AUDIO STREAM CONFIGS */
            $audioStreamConfigSoundHigh = new AudioStreamConfig();
            $audioStreamConfigSoundHigh->bitrate = 256000;
            $audioStreamConfigSoundHigh->defaultStreamId = 0;

            $audioStreamConfigSoundLow = new AudioStreamConfig();
            $audioStreamConfigSoundLow->bitrate = 156000;
            $audioStreamConfigSoundLow->defaultStreamId = 0;


            $audioStreamConfigSoundAndVoiceHigh = new AudioStreamConfig();
            $audioStreamConfigSoundAndVoiceHigh->bitrate = 256000;
            $audioStreamConfigSoundAndVoiceHigh->defaultStreamId = 1;

            $audioStreamConfigSoundAndVoiceLow = new AudioStreamConfig();
            $audioStreamConfigSoundAndVoiceLow->bitrate = 156000;
            $audioStreamConfigSoundAndVoiceLow->defaultStreamId = 1;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = 'TestEncodingProfile_' . $this->getName() . '@JobMultiLanguageTest';
            $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundHigh;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundLow;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundAndVoiceHigh;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigSoundAndVoiceLow;

            /* CREATE ENCODING PROFILE */

            return EncodingProfile::create($encodingProfileConfig);
        }
    }
