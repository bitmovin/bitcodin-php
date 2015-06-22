<?php
/**
 * Created by PhpStorm.
 * User: gcwioro
 * Date: 18.06.15
 * Time: 13:59
 */

use bitcodin\Bitcodin;
use bitcodin\EncodingProfile;
use bitcodin\AudioStreamConfig;
use bitcodin\VideoStreamConfig;

require_once __DIR__.'/vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

/*
*   ENCODING PROFILE
*/

/* CREATE VIDEO STREAM CONFIGS */
$videoStreamConfig1 = new VideoStreamConfig(
    array("bitrate" => 1024000,
          "height"  => 480,
          "width"   => 204));
$videoStreamConfig2 = new VideoStreamConfig(
    array("bitrate" => 2024000,
          "height"  => 1360,
          "width"   => 640));

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig = new AudioStreamConfig(array("bitrate" => 256000));

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create('MyApiCreatedEncodingProfile', array($videoStreamConfig1, $videoStreamConfig2), array($audioStreamConfig));

/* GET ENCODING PROFILE */
$encodingProfile = EncodingProfile::get($encodingProfile);
$encodingProfile = EncodingProfile::get($encodingProfile->encodingProfileId);

/* GET LIST OF ENCODING PROFILE */
$encodingProfileResponse = EncodingProfile::getList();
$encodingProfilesList = $encodingProfileResponse->profiles;   //List of Encoding Profiles
$encodingProfilesPerPage = $encodingProfileResponse->perPage;         //Encoding Profiles per page
$encodingProfilesTotal = $encodingProfileResponse->totalCount;        //Total count of Encoding Profiles