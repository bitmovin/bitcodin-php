<?php

    require_once __DIR__ . '/../vendor/autoload.php';

    /* CONFIGURATION */
    bitcodin\Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

    $jobId = 123456; //ENTER THE THE ID OF THE ENCODING YOU WANT TO TRANSFER
    $outputId = 123456; //ENTER THE THE ID OF THE OUTPUT YOU WANT YOUR ENCODING TRANSFERRED TO

    $encoding = bitcodin\Job::get($jobId);
    $output = \bitcodin\Output::get($outputId);
    $response = $encoding->transfer($output);

    var_dump($response);