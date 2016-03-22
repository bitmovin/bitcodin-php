<?php
/**
 * Created by PhpStorm.
 * User: akopper
 * Date: 07.03.2016
 * Time: 13:32
 */


use bitcodin\Bitcodin;
use bitcodin\Webhook;
use bitcodin\WebhookSubscription;

require_once __DIR__.'/../vendor/autoload.php';


/* CONFIGURATION */
// You can find your api key in the settings menu. Your account (top upper corner) -> Settings -> API
Bitcodin::setApiToken('your api key');


// get a list of all available webhooks
    /** @var Webhook $availableWebhooks */
    $availableWebhooks = Webhook::listAvailableWebhooks();
// find a webhook
    /** @var Webhook $jobFinishedWebhook */
    $jobFinishedWebhook = Webhook::find("job.finished");
// subscribe to the webhook
    /** @var WebhookSubscription $myJobFinishedSubscription */
    $myJobFinishedSubscription = $jobFinishedWebhook->subscribe("http://www.yourdomain.org/callbacks/job.finished");
// fetch a list of your current subscriptions for this event
    /** @var WebhookSubscription[] $activeSubscriptions */
    $activeSubscriptions = $jobFinishedWebhook->listSubscriptions();
// receive a list of connection attempts to your provided url
    /** @var mixed $callbackAttempts */
    $callbackAttempts = $myJobFinishedSubscription->listEvents();
// To delete a subscription you must execute the function on the
    $myJobFinishedSubscription->unsubscribe();


