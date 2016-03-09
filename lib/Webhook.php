<?php
/**
 * Created by PhpStorm.
 * User: cwioro
 * Date: 18.06.15
 * Time: 13:27
 */

namespace bitcodin;

/**
 * Class Job
 * @package bitcodin
 */
class Webhook extends ApiResource
{

    const URL_WEBHOOK = '/notifications/webhook';

    /**
     * @var string name of the webhook
     */
    public $name;

    /**
     * @var string description of the webhook
     */
    public $description;

    /**
     * @param $webhookName
     * @return Webhook
     */
    public static function find($webhookName)
    {
        $response = self::_getRequest(self::URL_WEBHOOK . "/" . $webhookName, 200);
        $webhook = new Webhook(json_decode($response->getBody()->getContents()));
        return $webhook;
    }

    /**
     * @param $callbackUrl
     * @return WebhookSubscription resulting subscription object
     */
    public function subscribe($callbackUrl)
    {
        return WebhookSubscription::create($this, $callbackUrl);
    }

    /**
     * @return WebhookSubscription[]
     */
    public function listSubscriptions()
    {
        return WebhookSubscription::listSubscriptions($this);
    }

    /**
     * @return mixed
     */
    public static function listAvailableWebhooks()
    {
        $response = self::_getRequest(self::URL_WEBHOOK, 200);
        return json_decode($response);
    }

    /**
     * @return string
     */
    public function toRequestBody()
    {
        $requestValues = array();
        $requestValues['event'] = $this->name;
        $requestValues['url'] = $this->url;

        return json_encode($requestValues);
    }

}