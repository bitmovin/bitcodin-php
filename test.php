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

require_once __DIR__.'/vendor/autoload.php';


/* CONFIGURATION */
Bitcodin::setApiToken('1d688e26c99280988c510c31d13c07305b51ec56a2946432ce6c77135aa7e8a7');


/*
*   INPUT
*/

/* CREATE INPUT */
$input = UrlInput::create('http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv');
//{formUrl: "test", url: "ftp://test", username: "user", password: "pw", isSaving: true}
//$ftpInput = FtpInput::create(array('url' => 'ftp://test.asdf', 'username'=> 'myUsername', 'password'=> 'topSecret'));
/* ANALYZE INPUT */
UrlInput::analyze($input);


/*
*   ENCODING PROFILE
*/

/* CREATE VIDEO STREAM CONFIGS */
$videoStreamConfig1 = new VideoStreamConfig(
    array("bitrate" => 1024000,
          "height"  => 1080,
          "width"   => 1920));
$videoStreamConfig2 = new VideoStreamConfig(
    array("bitrate" => 1024000,
          "height"  => 360,
          "width"   => 640));
/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig = new AudioStreamConfig(array("bitrate" => 256000));

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create('MyEncodingProfile', array($videoStreamConfig1, $videoStreamConfig2), $audioStreamConfig);

/* CREATE JOB */
$job = Job::create(array('inputId'           => $input,
                         'encodingProfileId' => $encodingProfile,
                         'manifestTypes'     => array('mpd')
                        )
                    );



var_dump($job);