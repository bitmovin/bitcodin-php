<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:59
 */

use bitcodin\Bitcodin;
use bitcodin\UrlInput;
use bitcodin\EncodingProfile;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\Job;
use bitcodin\FtpInput;
use bitcodin\ManifestTypes;

require_once __DIR__.'/vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

/* CREATE INPUT */
$input = UrlInput::create(['url' => 'https://www.dropbox.com/s/velqx3q1han8boe/bla-16.avi?dl=0']);

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

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    echo 'Job ['.$job->jobId.']: Status['.$job->status."]\n";
    sleep(1);
} while($job->status != Job::STATUS_FINISHED);

