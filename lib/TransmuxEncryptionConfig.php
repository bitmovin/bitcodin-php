<?php
    /**
     * Created by PhpStorm.
     * User: ThinkPad User
     * Date: 13.07.2016
     * Time: 11:28
     */

    namespace bitcodin;


    class TransmuxEncryptionConfig implements \JsonSerializable
    {
        /** @var  string 128-bit key in hex (32 characters). Example: 123456789ABCDEF123456789ABCDEF12 */
        private $keyAscii;
        /** @var  string Correct format is 128-bit key in hex (32 characters). Example: 123456789ABCDEF123456789ABCDEF12" */
        private $kid;
        /** @var  string 128-bit key in hex (32 characters) or 'random'. Example: 123456789ABCDEF123456789ABCDEF12 */
        private $saltKey;

        /**
         * TransmuxEncryptionConfig constructor.
         *
         * @param        $keyAscii 128-bit key in hex (32 characters). Example: 123456789ABCDEF123456789ABCDEF12"
         * @param        $kid      128-bit key in hex (32 characters). Example: 123456789ABCDEF123456789ABCDEF12"
         * @param string $saltKey  (optional, 'random' by default) 128-bit key in hex (32 characters) or 'random'.
         *                         Example: 123456789ABCDEF123456789ABCDEF12
         */
        public function __construct($keyAscii, $kid, $saltKey = "random")
        {
            $this->keyAscii = $keyAscii;
            $this->kid = $kid;
            $this->saltKey = $saltKey;
        }

        /**
         * @return string
         */
        public function getKeyAscii()
        {
            return $this->keyAscii;
        }

        /**
         * @param string $keyAscii
         */
        public function setKeyAscii($keyAscii)
        {
            $this->keyAscii = $keyAscii;
        }

        /**
         * @return string
         */
        public function getKid()
        {
            return $this->kid;
        }

        /**
         * @param string $kid
         */
        public function setKid($kid)
        {
            $this->kid = $kid;
        }

        /**
         * @return string
         */
        public function getSaltKey()
        {
            return $this->saltKey;
        }

        /**
         * @param string $saltKey
         */
        public function setSaltKey($saltKey)
        {
            $this->saltKey = $saltKey;
        }

        function jsonSerialize()
        {
            return $this->toArray();
        }

        public function toArray()
        {
            $array = array(
                "keyAscii" => $this->getKeyAscii(),
                "kid" => $this->getKid(),
                "saltKey" => $this->getSaltKey()
            );

            return $array;
        }
    }