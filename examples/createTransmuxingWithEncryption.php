<?php

    require_once __DIR__ . '/../vendor/autoload.php';

    /* CONFIGURATION */
    //TODO: INSERT YOUR API KEY HERE
    $apiKey = "YOUR_API_KEY";
    bitcodin\Bitcodin::setApiToken($apiKey); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

    /* GET ENCODING */
    $jobId = 123456; //TODO: INSERT THE ID OF YOUR ENCODING YOU WANT TO ENCRYPT
    $job = bitcodin\Job::get($jobId);

    /* first audio and video representation will be used for the encrypted transmuxing */
    $videoRepresentationId = $job->encodingProfiles[0]->videoStreamConfigs[0]->representationId;
    $audioRepresentationIds = array( $job->encodingProfiles[0]->audioStreamConfigs[0]->representationId );

    /**
     * Please see for more details about transmuxing:
     * https://bitmovin.com/encoding-documentation/encoder-api-reference-documentation/#/reference/transmux-*beta*
     */
    $outputFilename = "transmuxed_and_encrypted_video.mp4";
    $key = "YOUR_KEY";
    $kid = "YOUR_KID";
    $encryptionConfig = new \bitcodin\TransmuxEncryptionConfig($key, $kid);
    $transmuxConfig = new bitcodin\TransmuxConfig($jobId, $videoRepresentationId, $audioRepresentationIds, $outputFilename, $encryptionConfig);
    $transmuxing = bitcodin\Transmuxing::create($transmuxConfig);

    do {
        $transmuxing->update();
        sleep(5);
    } while ($transmuxing->getStatus() != bitcodin\Transmuxing::STATUS_FINISHED && $transmuxing->getStatus() != bitcodin\Transmuxing::STATUS_ERROR);

    echo "Transmuxing succeeded!\n";
    echo "URL to your transmuxed content: " . $transmuxing->outputUrl;