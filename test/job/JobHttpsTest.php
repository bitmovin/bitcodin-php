<?php
    /**
     * Created by PhpStorm.
     * User: dmoser
     * Date: 23.05.2016
     * Time: 13:12
     */

    namespace test\job;

    require_once __DIR__ . '/../../vendor/autoload.php';


    use bitcodin\AudioStreamConfig;
    use bitcodin\Bitcodin;
    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use bitcodin\HttpInputConfig;
    use bitcodin\Input;
    use bitcodin\Job;
    use bitcodin\JobConfig;
    use bitcodin\ManifestTypes;
    use bitcodin\VideoStreamConfig;


    class JobHttpsTest extends AbstractJobTest
    {

        const URL_FILE = 'http://bitbucketireland.s3.amazonaws.com/Sintel-original-short.mkv';

        public function setUp()
        {
            parent::setUp();
            Bitcodin::enableHttps();
        }

        /** TEST FAST-JOB CREATION */

        /**
         * @test
         * @return Job
         */
        public function createJob()
        {
            $inputConfig = new HttpInputConfig();
            $inputConfig->url = self::URL_FILE;
            $input = Input::create($inputConfig);

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

            /* CREATE ENCODING PROFILE */
            $encodingProfile = EncodingProfile::create($encodingProfileConfig);

            $jobConfig = new JobConfig();
            $jobConfig->encodingProfile = $encodingProfile;
            $jobConfig->input = $input;
            $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
            $jobConfig->manifestTypes[] = ManifestTypes::MPD;

            /* CREATE JOB */
            $job = Job::create($jobConfig);

            $this->assertInstanceOf(Job::class, $job);
            $this->assertNotNull($job->jobId);
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }
    }
