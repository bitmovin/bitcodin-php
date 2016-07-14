<?php

    namespace bitcodin;


    class TransmuxConfig
    {
        /** @var int */
        public $jobId;
        /** @var null|string */
        public $filename;
        /** @var null|string */
        public $videoRepresentationId;
        /** @var array */
        public $audioRepresentationIds;
        /** @var  TransmuxEncryptionConfig */
        private $encryptionConfig;

        /**
         * TransmuxConfig constructor.
         *
         * @param                               $jobId
         * @param null                          $videoRepresentationId
         * @param array                         $audioRepresentationIds
         * @param null                          $filename
         * @param TransmuxEncryptionConfig|NULL $encryptionConfig
         */
        public function __construct($jobId, $videoRepresentationId = NULL, array $audioRepresentationIds = array(), $filename = NULL, TransmuxEncryptionConfig $encryptionConfig = NULL)
        {
            $this->jobId = $jobId;
            $this->filename = $filename;
            $this->videoRepresentationId = $videoRepresentationId;
            $this->audioRepresentationIds = $audioRepresentationIds;
            $this->encryptionConfig = $encryptionConfig;
        }


        /**
         * @return string
         */
        public function getRequestBody()
        {
            $array = array(
                "jobId" => $this->jobId,
            );
            $filename = $this->getFilename();
            $videoRepresentationId = $this->getVideoRepresentationId();
            $audioRepresentationIds = $this->getAudioRepresentationIds();
            $encryptionConfig = $this->getEncryptionConfig();

            if (!is_null($filename)) {
                $array["filename"] = $filename;
            }
            if (is_numeric($videoRepresentationId)) {
                $array["videoRepresentationId"] = $videoRepresentationId;
            }
            if (is_array($audioRepresentationIds) && !empty($audioRepresentationIds)) {
                $array["audioRepresentationIds"] = $audioRepresentationIds;
            }
            if ($encryptionConfig instanceof TransmuxEncryptionConfig) {
                $array["encryptionConfig"] = $encryptionConfig;
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

        /**
         * @return TransmuxEncryptionConfig
         */
        public function getEncryptionConfig()
        {
            return $this->encryptionConfig;
        }

        /**
         * @param TransmuxEncryptionConfig $encryptionConfig
         */
        public function setEncryptionConfig($encryptionConfig)
        {
            $this->encryptionConfig = $encryptionConfig;
        }
    }