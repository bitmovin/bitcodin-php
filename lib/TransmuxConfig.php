<?php

namespace bitcodin;


class TransmuxConfig
{
    public $jobId;
    public $filename;
    public $videoRepresentationId;
    public $audioRepresentationIds;

    /**
     * TransmuxConfig constructor.
     *
     * @param int    $jobId
     * @param string $filename
     * @param string $videoRepresentationId
     * @param array  $audioRepresentationIds
     */
    public function __construct($jobId, $videoRepresentationId = NULL, array $audioRepresentationIds = array(), $filename = NULL)
    {
        $this->jobId = $jobId;
        $this->filename = $filename;
        $this->videoRepresentationId = $videoRepresentationId;
        $this->audioRepresentationIds = $audioRepresentationIds;
    }


    /**
     * @return string
     */
    public function getRequestBody()
    {
        $array = array(
            "jobId" => $this->jobId,
        );

        if(!is_null($this->filename)) {
            $array["filename"] = $this->filename;
        }
        if(is_numeric($this->videoRepresentationId)) {
            $array["videoRepresentationId"] = $this->videoRepresentationId;
        }
        if(is_array($this->audioRepresentationIds) && !empty($this->audioRepresentationIds)) {
            $array["audioRepresentationIds"] = $this->audioRepresentationIds;
        }

        return json_encode($array);
    }

    /**
     * @return null|string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param null|string $filename
     *
     * @return TransmuxConfig
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getVideoRepresentationId()
    {
        return $this->videoRepresentationId;
    }

    /**
     * @param null|string $videoRepresentationId
     *
     * @return TransmuxConfig
     */
    public function setVideoRepresentationId($videoRepresentationId)
    {
        $this->videoRepresentationId = $videoRepresentationId;

        return $this;
    }

    /**
     * @return array
     */
    public function getAudioRepresentationIds()
    {
        return $this->audioRepresentationIds;
    }

    /**
     * @param array $audioRepresentationIds
     *
     * @return TransmuxConfig
     */
    public function setAudioRepresentationIds($audioRepresentationIds)
    {
        $this->audioRepresentationIds = $audioRepresentationIds;

        return $this;
    }
    
}