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
Bitcodin::setApiToken('your api token');

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


// you can also find a subscription by it's id.
// you might have to store the id yourself or get it from the list of subscriptions like shown above
/** @var WebhookSubscription $mySubscription */
$mySubscription = WebhookSubscription::find($activeSubscriptions[0]->id);
// ... and then use it in the same way.
/** @var mixed $events */
$events = $mySubscription->listEvents();
// To delete a subscription you must execute the function on the
$myJobFinishedSubscription->unsubscribe();