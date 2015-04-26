<?php
    namespace Firebase;

    /**
     * Interface FirebaseInterface
     *
     * @package Firebase
     */
    interface FirebaseInterface
    {
        /**
         * @param $token
         *
         * @return mixed
         */
        public function setToken($token);

        /**
         * @param $baseURI
         *
         * @return mixed
         */
        public function setBaseURI($baseURI);

        /**
         * @param $seconds
         *
         * @return mixed
         */
        public function setTimeOut($seconds);

        /**
         * @param      $path
         * @param      $data
         * @param bool $async
         *
         * @return mixed
         */
        public function set($path, $data, $async = false);

        /**
         * @param      $path
         * @param      $data
         * @param bool $async
         *
         * @return mixed
         */
        public function push($path, $data, $async = false);

        /**
         * @param      $path
         * @param      $data
         * @param bool $async
         *
         * @return mixed
         */
        public function update($path, $data, $async = false);

        /**
         * @param $path
         *
         * @return mixed
         */
        public function get($path);

        /**
         * @param      $path
         * @param bool $async
         *
         * @return mixed
         */
        public function delete($path, $async = false);
    }
