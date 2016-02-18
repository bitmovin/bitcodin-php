<?php

namespace bitcodin;


class TransmuxJob extends ApiResource
{
    const URL_CREATE = '/transmuxjob';
    const URL_GET = '/transmuxjob/{id}';

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string[]
     */
    public $files;

    /**
     * @param TransmuxConfig $transmuxConfig
     * @return TransmuxJob
     */
    static function create($transmuxConfig)
    {
        $response = self::_postRequest(self::URL_CREATE, $transmuxConfig->getRequestBody(), 200);
        return new self(json_decode($response->getBody()->getContents()));
    }

    public function update()
    {
        $response = self::_getRequest(str_replace('{id}', $this->id, self::URL_GET), 200);
        $transmuxJob = json_decode($response->getBody()->getContents());
        self::_copy($transmuxJob);
    }
}