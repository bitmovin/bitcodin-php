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
Bitcodin::setApiToken('3bf186b02a8cc7caf40b1dbad22ad5421f0390f685ca4dca88f31d28135b4709');

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
