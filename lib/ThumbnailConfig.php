<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 13.11.15
 * Time: 10:06
 */

namespace bitcodin;


class ThumbnailConfig
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
    public $position;

    /**
     * @var string
     */
    public $filename;

    public function getRequestBody()
    {
        $array = array(
            "jobId" => $this->jobId,
            "height" => $this->height,
            "position" => $this->position
        );

        if(isset($this->filename))
            $array["filename"] = $this->filename;

        return json_encode($array);
    }
}