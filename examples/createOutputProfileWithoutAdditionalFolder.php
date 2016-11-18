<?php
    use bitcodin\AwsRegion;
    use bitcodin\Bitcodin;
    use bitcodin\Output;
    use bitcodin\S3OutputConfig;

    require_once __DIR__ . '/../vendor/autoload.php';

    /* CONFIGURATION */
    Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

    $outputConfig = new S3OutputConfig();
    $outputConfig->name = "TestS3Output";
    $outputConfig->accessKey = "yourAWSAccessKey";
    $outputConfig->secretKey = "yourAWSSecretKey";
    $outputConfig->bucket = "yourBucketName";
    $outputConfig->region = AwsRegion::EU_WEST_1;
    $outputConfig->prefix = "path/to/your/outputDirectory";
    $outputConfig->makePublic = false;        //This flag determines whether the files put on S3 will be publicly accessible via HTTP Url or not
    $outputConfig->createSubDirectory = false;  // Controls the creation of an additional subfolder at your transfer-destination to prevent accidentally overwriting already existing files with the same name

    $output = Output::create($outputConfig);

