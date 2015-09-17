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
use bitcodin\WatermarkConfig;
use test\BitcodinApiTestBaseClass;


class EncodingProfileWatermarkTest extends BitcodinApiTestBaseClass {

    /**
     * @test
     * @expectedException               bitcodin\exceptions\BitcodinException
     */
    public function createWatermarkConfigWithNegativeTop()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $watermarkConfig = new WatermarkConfig();
        $watermarkConfig->top = -10;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->watermarkConfig = $watermarkConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    /**
     * @test
     * @expectedException               bitcodin\exceptions\BitcodinException
     */
    public function createWatermarkConfigWithNegativeRightValue()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $watermarkConfig = new WatermarkConfig();
        $watermarkConfig->right = -1;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->watermarkConfig = $watermarkConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    /**
     * @test
     * @expectedException               bitcodin\exceptions\BitcodinException
     */
    public function createWatermarkConfigWithNegativeLeftValue()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $watermarkConfig = new WatermarkConfig();
        $watermarkConfig->left = -10;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->watermarkConfig = $watermarkConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    /**
     * @test
     * @expectedException               bitcodin\exceptions\BitcodinException
     */
    public function createWatermarkConfigWithNegativeBottom()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $watermarkConfig = new WatermarkConfig();
        $watermarkConfig->bottom = -2;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->watermarkConfig = $watermarkConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    /**
     * @test
     * @expectedException               bitcodin\exceptions\BitcodinException
     */
    public function createWatermarkConfigWithBottomAndTop()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $watermarkConfig = new WatermarkConfig();
        $watermarkConfig->top = 10;
        $watermarkConfig->bottom = 2;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->watermarkConfig = $watermarkConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    /**
     * @test
     * @expectedException               bitcodin\exceptions\BitcodinException
     */
    public function createWatermarkConfigWithNoImage()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $watermarkConfig = new WatermarkConfig();
        $watermarkConfig->top = 10;
        $watermarkConfig->left = 2;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->watermarkConfig = $watermarkConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    private function getEncodingProfileConfigTemplate()
    {
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


        return $encodingProfileConfig;
    }


}

