<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 04.11.15
 * Time: 16:16
 */

namespace bitcodin;


class VttMpdConfig
{
    /**
     * @var int
     */
    public $jobId;

    /**
     * @var VttSubtitle[]
     */
    public $subtitles;

    public function getRequestBody()
    {
        $array = array(
            "jobId" => $this->jobId,
            "subtitles" => $this->subtitles
        );
        return json_encode($array);
    }
}