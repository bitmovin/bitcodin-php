<?php

use bitcodin\Bitcodin;
use bitcodin\Sprite;
use bitcodin\SpriteConfig;

require_once __DIR__.'/../vendor/autoload.php';

Bitcodin::setApiToken('insertYourApiKey'); // Your can find your api key in the settings menu. Your account (right corner) -> Settings -> API

$spriteConfig = new SpriteConfig();
$spriteConfig->jobId = 1234; // insert your jobId here
$spriteConfig->height = 90;
$spriteConfig->width = 120;
$spriteConfig->distance = 10;
$spriteConfig->async = true;

$sprite = Sprite::create($spriteConfig);
echo "Sprite ID: " . $sprite->id . "\n";
echo "Sprite VTT URL: " . $sprite->vttUrl . "\n";
echo "Sprite URL: " . $sprite->spriteUrl . "\n";
echo "Sprite State: " . $sprite->state . "\n";

while ($sprite->state != 'FINISHED' && $sprite->state != 'ERROR')
{
    sleep(1);
    $sprite = Sprite::get($sprite->id);
    echo "Sprite ID: " . $sprite->id . "\n";
    echo "Sprite VTT URL: " . $sprite->vttUrl . "\n";
    echo "Sprite URL: " . $sprite->spriteUrl . "\n";
    echo "Sprite State: " . $sprite->state . "\n";
}
