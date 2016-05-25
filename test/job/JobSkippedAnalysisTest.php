<?php
    /**
     * Created by David Moser <david.moser@bitmovin.net>
     * Date: 25.01.16
     * Time: 14:33
     */

    namespace test\job;

    use bitcodin\AudioMetaData;
    use bitcodin\AudioStreamConfig;
    use bitcodin\Bitcodin;
    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use bitcodin\HttpInputConfig;
    use bitcodin\Input;
    use bitcodin\Job;
    use bitcodin\JobConfig;
    use bitcodin\JobSpeedTypes;
    use bitcodin\ManifestTypes;
    use bitcodin\VideoStreamConfig;

    class JobSkippedAnalysisTest extends AbstractJobTest
    {
        const URL_FILE = 'http://bitbucketireland.s3.amazonaws.com/Sintel-original-short.mkv';

        public function setUp()
        {
            parent::setUp();
        }

        public function testMultiLanguageJob()
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;
            $inputConfig->skipAnalysis = true;

            $input = Input::create($inputConfig);

            $audioMetaDataJustSound = new AudioMetaData();
            $audioMetaDataJustSound->defaultStreamId = 0;
            $audioMetaDataJustSound->label = 'Just Sound';
            $audioMetaDataJustSound->language = 'de';

            $audioMetaDataSoundAndVoice = new AudioMetaData();
            $audioMetaDataSoundAndVoice->defaultStreamId = 1;
            $audioMetaDataSoundAndVoice->label = 'Sound and Voice';
            $audioMetaDataSoundAndVoice->language = 'en';

            /* CREATE VIDEO STREAM CONFIG */
            $videoStreamConfig = new VideoStreamConfig();
            $videoStreamConfig->bitrate = 2400000;
            $videoStreamConfig->height = 720;
            $videoStreamConfig->width = 1280;


            /* CREATE AUDIO STREAM CONFIGS */
            $audioStreamConfig = new AudioStreamConfig();
            $audioStreamConfig->bitrate = 256000;

            $encodingProfileConfig = new EncodingProfileConfig();
            $encodingProfileConfig->name = 'SkippedAnalysisProfile';
            $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
            $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

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
    }
