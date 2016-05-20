<?php

require_once __DIR__.'/../vendor/autoload.php';

use bitcodin\Bitcodin;

/* CONFIGURATION */
Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API
Bitcodin::enableHttps();

// Use the API as always