<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */


require_once __DIR__ . '/../../vendor/autoload.php';


use bitcodin\Bitcodin;
use bitcodin\EncodingProfile;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\EncodingProfileConfig;
use test\BitcodinApiTestBaseClass;


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
        $encodingProfileConfig->name = $this->getName().'EncodingProfile';
        $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
        $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

        /* CREATE ENCODING PROFILE */
        $encodingProfile = EncodingProfile::create($encodingProfileConfig);

        $this->checkEncodingProfile($encodingProfile);
        return $encodingProfile;
    }

    public function testCreateErrorEncodingProfile()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $encodingProfileConfig = new EncodingProfileConfig();
        $this->setExpectedException('bitcodin\exceptions\BitcodinException');
        /* CREATE ENCODING PROFILE */
        EncodingProfile::create($encodingProfileConfig);

    }


    /**
     * @depends testCreateEncodingProfile
     */
    public function testGetEncodingProfile(EncodingProfile $encodingProfile)
    {
        $encodingProfileNew = EncodingProfile::get($encodingProfile);
        $this->checkEncodingProfile($encodingProfile);
        $this->assertEquals($encodingProfileNew->encodingProfileId, $encodingProfile->encodingProfileId);
    }

    public function testGetList()
    {
        foreach(EncodingProfile::getListAll() as $encodingProfile)
        {
            $this->checkEncodingProfile($encodingProfile);
        }
    }

    private function checkEncodingProfile(EncodingProfile $encodingProfile)
    {
        $this->assertInstanceOf('bitcodin\EncodingProfile', $encodingProfile);
        $this->assertTrue(is_numeric($encodingProfile->encodingProfileId), 'encodingProfileId not set');
        $this->assertTrue(is_array($encodingProfile->videoStreamConfigs), 'videoStreamConfigs not set');
        $this->assertTrue(is_array($encodingProfile->audioStreamConfigs), 'audioStreamConfigs not set');
    }

}
