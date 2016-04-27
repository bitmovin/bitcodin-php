<?php

namespace bitcodin;


class SpriteConfig
{
    /**
     * @var int
     */
    public $jobId;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $distance;

    /**
     * @var bool
     */
    public $async;

    public function getRequestBody()
    {
        $array = array(
            "jobId" => $this->jobId,
            "height" => $this->height,
            "width" => $this->width,
            "distance" => $this->distance
        );

        if(isset($this->async))
            $array["async"] = $this->async;        

        return json_encode($array);
    }
}
