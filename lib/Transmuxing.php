<?php

namespace bitcodin;


class Transmuxing extends ApiResource
{
    const URL_CREATE = '/transmux';
    const URL_GET = '/transmux/{id}';

    const STATUS_FINISHED = "finished";
    const STATUS_ERROR = "error";

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $status;

    /**
     * @param TransmuxConfig $transmuxConfig
     *
*@return Transmuxing
     */
    static function create(TransmuxConfig $transmuxConfig)
    {
        $response = self::_postRequest(self::URL_CREATE, $transmuxConfig->getRequestBody(), 201);
        return new self(json_decode($response->getBody()->getContents()));
    }

    public function update()
    {
        $response = self::_getRequest(str_replace('{id}', $this->id, self::URL_GET), 200);
        $transmuxing = json_decode($response->getBody()->getContents());
        self::_copy($transmuxing);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Transmuxing
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Transmuxing
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }


}