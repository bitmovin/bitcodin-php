<?php

    require_once __DIR__ . '/../vendor/autoload.php';

    use bitcodin\AudioStreamConfig;
    use bitcodin\Bitcodin;
    use bitcodin\ClearKeyEncryptionConfig;
    use bitcodin\DRMEncryptionMethods;
    use bitcodin\EncodingProfile;
    use bitcodin\EncodingProfileConfig;
    use bitcodin\HttpInputConfig;
    use bitcodin\Input;
    use bitcodin\Job;
    use bitcodin\JobConfig;
    use bitcodin\JobSpeedTypes;
    use bitcodin\ManifestTypes;
    use bitcodin\VideoStreamConfig;

    /* CONFIGURATION */
    // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API
    Bitcodin::setApiToken('INSERTYOURAPIKEY');

    $inputConfig = new HttpInputConfig();
    $inputConfig->url = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';
    $input = Input::create($inputConfig);

    $encodingProfileConfig = new EncodingProfileConfig();
    $encodingProfileConfig->name = 'FullHD + HD Example Profile';

    /* CREATE VIDEO STREAM CONFIGS */
    $videoStreamConfig1 = new VideoStreamConfig();
    $videoStreamConfig1->bitrate = 4800000;
    $videoStreamConfig1->width = 1920;
    $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig1;

    $videoStreamConfig2 = new VideoStreamConfig();
    $videoStreamConfig2->bitrate = 2400000;
    $videoStreamConfig2->width = 1280;
    $encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig2;

    /* CREATE AUDIO STREAM CONFIGS */
    $audioStreamConfig = new AudioStreamConfig();
    $audioStreamConfig->bitrate = 128000;
    $encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

    /* CREATE ENCODING PROFILE */
    $encodingProfile = EncodingProfile::create($encodingProfileConfig);

    /* CREATE CLEARKEY CONFIG */
    $clearKeyEncryptionConfig = new ClearKeyEncryptionConfig();
    $clearKeyEncryptionConfig->key = '100b6c20940f779a4589152b57d2dacb';
    $clearKeyEncryptionConfig->kid = 'eb676abbcb345e96bbcf616630f1a3da';
    $clearKeyEncryptionConfig->method = DRMEncryptionMethods::MPEG_CENC;

    $jobConfig = new JobConfig();
    $jobConfig->encodingProfile = $encodingProfile;
    $jobConfig->input = $input;
    $jobConfig->manifestTypes[] = ManifestTypes::MPD;
    $jobConfig->manifestTypes[] = ManifestTypes::M3U8;
    $jobConfig->speed = JobSpeedTypes::PREMIUM;
    $jobConfig->duration = 60;
    $jobConfig->drmConfig = $clearKeyEncryptionConfig;

    /* CREATE JOB */
    $job = Job::create($jobConfig);

    /* WAIT TIL JOB IS FINISHED */
    do {
        $job->update();
        echo date_create()->format("d.m.Y H:i:s") . " Encoding Status: " . $job->status . "\n";
        sleep(5);
    } while ($job->status != Job::STATUS_FINISHED && $job->status != Job::STATUS_ERROR);