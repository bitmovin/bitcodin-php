<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../../vendor/autoload.php';

use bitcodin\Bitcodin;
use bitcodin\Input;
use bitcodin\AudioStreamConfig;
use bitcodin\EncodingProfile;
use bitcodin\ManifestTypes;
use bitcodin\Job;
use bitcodin\HttpInputConfig;
use bitcodin\EncodingProfileConfig;
use bitcodin\JobConfig;
use bitcodin\JobSpeedTypes;
use test\job\AbstractJobTest;

class JobAudioOnlyTest extends AbstractJobTest {

    const URL_FILE =  'http://bitbucketireland.s3.amazonaws.com/Sintel-two-audio-streams-short.mkv';

    /** TEST JOB CREATION */

    public function testAudioOnlyJob()
    {
        Bitcodin::setApiToken($this->getApiKey());
        $inputConfig = new HttpInputConfig();
        $inputConfig->url = self::URL_FILE;
        $input = Input::create($inputConfig);



        /* CREATE ENCODING PROFILE */
        $encodingProfile = $this->getMultiLanguageEncodingProfile();

        $jobConfig = new JobConfig();
        $jobConfig->encodingProfile = $encodingProfile;
        $jobConfig->input = $input;
        $jobConfig->manifestTypes[] = ManifestTypes::MPD;
        $jobConfig->speed = JobSpeedTypes::STANDARD;


        /* CREATE JOB */
        $job = Job::create($jobConfig);

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }




    /**
     * @return EncodingProfile
     */
    private function getMultiLanguageEncodingProfile()
    {



        $audioStreamConfigGermanLow = new AudioStreamConfig();
        $audioStreamConfigGermanLow->bitrate = 156000;
        $audioStreamConfigGermanLow->defaultStreamId = 0;



        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name =  'TestEncodingProfile_'.$this->getName().'@JobMultiLanguageTest';

        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfigGermanLow;

        /* CREATE ENCODING PROFILE */
        return EncodingProfile::create($encodingProfileConfig);
    }
}
