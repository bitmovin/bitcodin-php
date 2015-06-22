<?php
/**
 * Created by PhpStorm.
 * User: gcwioro
 * Date: 18.06.15
 * Time: 13:59
 */

use bitcodin\Bitcodin;
use bitcodin\UrlInput;
use bitcodin\FtpInput;
use bitcodin\Input;
use bitcodin\Job;
use bitcodin\EncodingProfile;
use bitcodin\ManifestTypes;

require_once __DIR__.'/vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

/* CREATE JOB */
$job = Job::create(array('inputId'           => Input::getList()->inputs[0], //assumes that one input exists
                         'encodingProfileId' => EncodingProfile::getList()->profiles[0], //assumes that one encodingprofile exists
                         'manifestTypes'     => [ManifestTypes::MPD, ManifestTypes::M3U8]
    )
);

/* WAIT TIL JOB IS FINISHED */
$job = Job::get($job);
do{
    $job->update();
    echo 'Job ['.$job->jobId.']: Status['.$job->status."]\n";
    sleep(1);
} while($job->status != Job::STATUS_FINISHED);



/* GET LIST OF JOBS */
$jobsResponse = Job::getList();
$jobs = $jobsResponse->jobs;       //List of inputs
$jobsPerPage = $jobsResponse->perPage;   //Inputs per page
$jobsTotal = $jobsResponse->totalCount;  //Total count of inputs
