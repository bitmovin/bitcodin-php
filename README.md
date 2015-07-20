# [![bitcodin](http://www.bitcodin.com/wp-content/uploads/2014/10/bitcodin-small.gif)](http://www.bitcodin.com)
[![Build Status](https://travis-ci.org/bitmovin/bitcodin-php.svg?branch=master)](https://travis-ci.org/bitmovin/bitcodin-php)
[![Coverage Status](https://coveralls.io/repos/bitmovin/bitcodin-php/badge.svg?branch=master)](https://coveralls.io/r/bitmovin/bitcodin-php?branch=master)

The bitcodin API for PHP is a seamless integration with the [bitcodin cloud transcoding system](http://www.bitcodin.com). It enables the generation of MPEG-DASH and HLS content in just some minutes.

Installation
------------

### Composer ###
 
  
To install with composer add the following to your `composer.json` file:
```js
{
"repositories": 
  [{
    "type": "git",
    "url": "ssh://git@github.com/bitmovin/bitcodin-php.git"
  }],
"require": 
  {
    "bitmovin/bitcodin-php": "dev-master"
  }
}
```
Then run `php composer.phar install`

Usage
-----

Before you can start using the api you need to set your API key in the Bitcodin class. Your API key can be found in the settings of your bitcodin user account, as shown in the figure below.

![APIKey](http://www.bitcodin.com/wp-content/uploads/2015/06/api_key.png)

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
$videoStreamConfig->bitrate = 1024000;
$videoStreamConfig->height = 480;
$videoStreamConfig->width = 202;

/* CREATE AUDIO STREAM CONFIGS */
$audioStreamConfig = new AudioStreamConfig();
$audioStreamConfig->bitrate = 256000;

$encodingProfileConfig = new EncodingProfileConfig();
$encodingProfileConfig->name = 'MyApiTestEncodingProfile';
$encodingProfileConfig->videoStreamConfigs[] = $videoStreamConfig;
$encodingProfileConfig->audioStreamConfigs[] = $audioStreamConfig;

/* CREATE ENCODING PROFILE */
$encodingProfile = EncodingProfile::create($encodingProfileConfig);

$jobConfig = new JobConfig();
$jobConfig->encodingProfile = $encodingProfile;
$jobConfig->input = $input;
$jobConfig->manifestTypes[] = ManifestTypes::M3U8;

/* CREATE JOB */
$job = Job::create($jobConfig);

/* WAIT TIL JOB IS FINISHED */
do{
    $job->update();
    sleep(1);
} while($job->status != Job::STATUS_FINISHED);


$outputConfig = new FtpOutputConfig();
$outputConfig->name = "TestS3Output";
$outputConfig->host = str_replace('ftp://', '', $this->getKey('ftpServer'));
$outputConfig->username = $this->getKey('ftpUser');
$outputConfig->password = $this->getKey('ftpPassword');

$output = Output::create($outputConfig);

/* TRANSFER JOB OUTPUT */
$job->transfer($output);

```
