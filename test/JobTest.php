<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/BitcodinApiTestBaseClass.php';

use bitcodin\Bitcodin;
use bitcodin\UrlInput;
use bitcodin\Input;
use bitcodin\FtpInput;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\EncodingProfile;
use bitcodin\ManifestTypes;
use bitcodin\Job;


class JobTest extends BitcodinApiTestBaseClass {

    const URL_FILE = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';

    /**
     * @var null|Job
     */
    private $job = null;

    public function testCreateJob()
    {
        /* CREATE INPUT */
        $input = UrlInput::create(['url' => self::URL_FILE]);

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig(
            array("bitrate" => 1024000,
                  "height"  => 480,
                  "width"   => 204));

        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig(array("bitrate" => 320000));

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create('MyEncodingProfile', array($videoStreamConfig), $audioStreamConfig);

        /* CREATE JOB */
        $job = Job::create(array('inputId'           => $input,
                                 'encodingProfileId' => $encodingProfile,
                                 'manifestTypes'     => [ManifestTypes::MPD]
            )
        );

        $this->assertInstanceOf('bitcodin\Job', $job);
        $this->assertNotNull($job->jobId);
        $this->assertNotEquals($job->status, Job::STATUS_ERROR);
        return $job;
    }

    /**
     * @depends JobTest::testCreateJob
     */
    public function testUpdateJob($job)
    {
        /* WAIT TIL JOB IS FINISHED */
        do{
            $job->update();
            $this->assertNotEquals($job->status, Job::STATUS_ERROR);
            sleep(1);
        } while($job->status != Job::STATUS_FINISHED);

        $this->assertEquals($job->status, Job::STATUS_FINISHED);
    }
}
