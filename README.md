# [![bitmovin](https://cloudfront-prod.bitmovin.com/wp-content/themes/Bitmovin-V-0.1/images/logo3.png)](http://www.bitmovin.com)
[![Build Status](https://travis-ci.org/bitmovin/bitcodin-php.svg?branch=master)](https://travis-ci.org/bitmovin/bitcodin-php)
[![Coverage Status](https://coveralls.io/repos/bitmovin/bitcodin-php/badge.svg?branch=master)](https://coveralls.io/r/bitmovin/bitcodin-php?branch=master)

The bitmovin API for PHP is a seamless integration with the [bitmovin cloud transcoding system](http://www.bitmovin.com). It enables the generation of MPEG-DASH and HLS content in just a few minutes.

Installation 
------------

### Composer ###
 
  
To install the api-client with composer, add the following to your `composer.json` file:  
```js
{
"require": 
  {
    "bitmovin/bitcodin-php": "1.14.*"
  }
}
```
Then run `php composer.phar install`

OR

run the following command: `php composer.phar require bitmovin/bitcodin-php:*`

Usage
-----

Before you can start using the api you need to **set your API key.**

Your API key can be found in the **settings of your bitmovin user account**, as shown in the figure below.

![APIKey](https://cloudfront-prod.bitmovin.com/wp-content/uploads/2016/04/api-key.png)

An example how you can set the bitcodin API is shown in the following:

```php
use bitcodin\Bitcodin;

Bitcodin::setApiToken('yourApiKey');
```

Example
-----
The following example demonstrates how to create a simple transcoding job and transfer it to an S3 output location:
```php
<?php

use bitcodin\Bitcodin;
use bitcodin\VideoStreamConfig;
use bitcodin\AudioStreamConfig;
use bitcodin\Job;
use bitcodin\JobConfig;
use bitcodin\Input;
use bitcodin\HttpInputConfig;
use bitcodin\EncodingProfile;
use bitcodin\EncodingProfileConfig;
use bitcodin\ManifestTypes;
use bitcodin\Output;
use bitcodin\FtpOutputConfig;

require_once __DIR__.'/vendor/autoload.php';

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$inputConfig = new HttpInputConfig();
$inputConfig->url = 'http://eu-storage.bitcodin.com/inputs/Sintel.2010.720p.mkv';
$input = Input::create($inputConfig);

/* CREATE VIDEO STREAM CONFIG */
$videoStreamConfig = new VideoStreamConfig();
//$videoStreamConfig->height = 720; //if you omit either width or height, our service will use the aspect ratio of your input-file
$videoStreamConfig->width = 1280;
$videoStreamConfig->bitrate = 1024000;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig = new AudioStreamConfig();
$audioStreamConfig->bitrate = 256000;

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'My first Encoding Profile';
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;
$jobConfig->manifestTypes[] = ManifestTypes::MPD;

/* CREATE JOB */
$job = Job::create($jobConfig);

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    sleep(1);
} while($job->status != Job::STATUS_FINISHED);

$outputConfig = new S3OutputConfig();
$outputConfig->name         = "My first S3 Output";
$outputConfig->accessKey    = "yourAWSAccessKey";
$outputConfig->secretKey    = "yourAWSSecretKey";
$outputConfig->bucket       = "yourBucketName";
$outputConfig->region       = AwsRegion::EU_WEST_1;
$outputConfig->prefix       = "path/to/your/output/destination";
$outputConfig->makePublic   = false;                            // This flag determines whether the files put on S3 will be publicly accessible via HTTP Url or not

$output = Output::create($outputConfig);

/* TRANSFER JOB OUTPUT */
$job->transfer($output);

```
