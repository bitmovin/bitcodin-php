<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 22.06.15
 * Time: 13:57
 */

namespace test\encodingprofile;

require_once __DIR__ . '/../../vendor/autoload.php';


use bitcodin\Bitcodin;
use bitcodin\EncodingProfile;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\EncodingProfileConfig;
use bitcodin\CroppingConfig;
use test\BitcodinApiTestBaseClass;


class EncodingCroppingProfileTest extends BitcodinApiTestBaseClass {

    public function __construct() {
        parent::__construct();

        Bitcodin::setApiToken($this->getApiKey());
    }

    /**
     * @test
     * @expectedException               \bitcodin\exceptions\BitcodinException
     */
    public function createCroppingConfigWithNegativeTop()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $croppingConfig = new CroppingConfig();
        $croppingConfig->top = -10;
        $croppingConfig->bottom = 2;
        $croppingConfig->right = 0;
        $croppingConfig->left = 10;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->croppingConfig = $croppingConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    /**
     * @test
     * @expectedException               \bitcodin\exceptions\BitcodinException
     */
    public function createCroppingConfigWithNegativeRightValue()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $croppingConfig = new CroppingConfig();
        $croppingConfig->top = 10;
        $croppingConfig->bottom = 2;
        $croppingConfig->right = -1;
        $croppingConfig->left = 10;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->croppingConfig = $croppingConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    /**
     * @test
     * @expectedException               \bitcodin\exceptions\BitcodinException
     */
    public function createCroppingConfigWithNegativeLeftValue()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $croppingConfig = new CroppingConfig();
        $croppingConfig->top = 10;
        $croppingConfig->bottom = 2;
        $croppingConfig->right = 1;
        $croppingConfig->left = -10;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->croppingConfig = $croppingConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    /**
     * @test
     * @expectedException               \bitcodin\exceptions\BitcodinException
     */
    public function createCroppingConfigWithNegativeBottom()
    {
        Bitcodin::setApiToken($this->getApiKey());

        $croppingConfig = new CroppingConfig();
        $croppingConfig->top = 10;
        $croppingConfig->bottom = -2;
        $croppingConfig->right = 1;
        $croppingConfig->left = 10;

        $encodingProfileConfig = $this->getEncodingProfileConfigTemplate();
        $encodingProfileConfig->croppingConfig = $croppingConfig;

        EncodingProfile::create($encodingProfileConfig);
    }

    private function getEncodingProfileConfigTemplate()
    {
        /* CREATE VIDEO STREAM CONFIG */
        $videoStreamConfig = new VideoStreamConfig();
        $videoStreamConfig->bitrate = 1024000;
        $videoStreamConfig->height = 202;
        $videoStreamConfig->width = 480;

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
