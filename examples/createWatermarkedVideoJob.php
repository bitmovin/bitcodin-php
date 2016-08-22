<?php

    require_once __DIR__ . '/../vendor/autoload.php';

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
    use bitcodin\WatermarkConfig;

    /* CONFIGURATION */
    Bitcodin::setApiToken('INSERTYOURAPIKEYHERE'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

    $inputConfig = new HttpInputConfig();
    $inputConfig->url = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';

    $input = Input::create($inputConfig);

    $encodingProfileConfig = new EncodingProfileConfig();
    $encodingProfileConfig->name = '[DEMO] Encoding Profile with Watermark';

    /* CREATE VIDEO STREAM CONFIGS */
    $videoStreamConfig1 = new VideoStreamConfig();
    $videoStreamConfig1->bitrate = 4800000;
    $videoStreamConfig1->height = 1080;
    $videoStreamConfig1->width = 1920;
    $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig1;

    $videoStreamConfig2 = new VideoStreamConfig();
    $videoStreamConfig2->bitrate = 2400000;
    $videoStreamConfig2->height = 720;
    $videoStreamConfig2->width = 1280;
    $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig2;

    $videoStreamConfig3 = new VideoStreamConfig();
    $videoStreamConfig3->bitrate = 1200000;
    $videoStreamConfig3->height = 480;
    $videoStreamConfig3->width = 854;
    $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig3;

    /* CREATE AUDIO STREAM CONFIGS */
    $audioStreamConfig = new AudioStreamConfig();
    $audioStreamConfig->bitrate = 156000;
    $audioStreamConfig->defaultStreamId = 0;
    $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

    /* CREATE WATERMARK */
    $watermarkConfig = new WatermarkConfig();
    $watermarkConfig->bottom = 200; // Watermark will be placed with a distance of 200 pixel to the bottom of the input video
    $watermarkConfig->right = 100;  // Watermark will be placed with a distance of 100 pixel to the right of the input video
    $watermarkConfig->image = '';
    $encodingProfileConfig->watermarkConfig = $watermarkConfig;

    /* CREATE ENCODING PROFILE */
    $encodingProfile = EncodingProfile::create($encodingProfileConfig);

    $jobConfig = new JobConfig();
    $jobConfig->input = $input;
    $jobConfig->encodingProfile = $encodingProfile;
    $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
    $jobConfig->manifestTypes[] = ManifestTypes::MPD;

    /* CREATE JOB */
    $job = Job::create($jobConfig);

    /* WAIT TIL JOB IS FINISHED */
    do {
        $job->update();
        sleep(1);
    } while ($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);