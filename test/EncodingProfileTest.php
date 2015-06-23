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
use bitcodin\Input;
use bitcodin\UrlInputConfig;
use bitcodin\FtpInputConfig;
use bitcodin\EncodingProfile;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\EncodingProfileConfig;



class EncodingProfileTest extends BitcodinApiTestBaseClass {

    const FTP_FILE = '/Homepage_Summer_v10.webm';
    const URL_FILE = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';


    public function testCreateEncodingProfile()
    {
        Bitcodin::setApiToken($this->getApiKey());

        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 480;
        $videoStreamConfig->width = 202;


        /* CREATE AUDIO STREAM CONFIGS */
        $audioStreamConfig = new AudioStreamConfig();
        $audioStreamConfig->bitrate = 256000;

        $encodingProfileConfig = new EncodingProfileConfig();
        $encodingProfileConfig->name = 'MyApiTestEncodingProfile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;


        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        $this->assertInstanceOf('bitcodin\EncodingProfile', $encodingProfile);
        $this->assertTrue(is_numeric($encodingProfile->encodingProfileId), 'encodingProfileId not set');
        $this->assertTrue(is_array($encodingProfile->videoStreamConfigs), 'videoStreamConfigs not set');
        $this->assertTrue(is_array($encodingProfile->audioStreamConfigs), 'audioStreamConfigs not set');

        return $encodingProfile;
    }



    /**
     * @depends EncodingProfileTest::testCreateEncodingProfile
     */
    public function testGetEncodingProfile(EncodingProfile $encodingProfile)
    {

        $encodingProfile = EncodingProfile::get($encodingProfile);
        $this->assertInstanceOf('bitcodin\EncodingProfile', $encodingProfile);
        $this->assertEquals($encodingProfile->encodingProfileId, $encodingProfile->encodingProfileId);
        $this->assertTrue(is_numeric($encodingProfile->encodingProfileId), 'encodingProfileId not set');
        $this->assertTrue(is_array($encodingProfile->videoStreamConfigs), 'videoStreamConfigs not set');
        $this->assertTrue(is_array($encodingProfile->audioStreamConfigs), 'audioStreamConfigs not set');
    }


    public function testGetList()
    {
        foreach(EncodingProfile::getListAll() as $encodingProfile)
        {
            $this->assertInstanceOf('bitcodin\EncodingProfile', $encodingProfile);
            $this->assertEquals($encodingProfile->encodingProfileId, $encodingProfile->encodingProfileId);
            $this->assertTrue(is_numeric($encodingProfile->encodingProfileId), 'encodingProfileId not set');
            $this->assertTrue(is_array($encodingProfile->videoStreamConfigs), 'videoStreamConfigs not set');
            $this->assertTrue(is_array($encodingProfile->audioStreamConfigs), 'audioStreamConfigs not set');

        }
    }

}
