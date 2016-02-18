<?php
/**
 * Created by David Moser <david.moser@bitmovin.net>
 * Date: 04.12.15
 * Time: 10:03
 */

namespace bitcodin;


class TransmuxConfig
{
    /**
     * @var int
     */
    public $jobId;

    /**
     * @return string
     */
    public function getRequestBody()
    {
        $array = array(
            "jobId" => $this->jobId
        );

        return json_encode($array);
    }
}