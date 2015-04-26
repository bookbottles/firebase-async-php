<?php
    namespace Firebase;

    use \Exception;


    /**
     * Firebase PHP Client Library
     *
     * @author Tamas Kalman <ktamas77@gmail.com>
     * @url    https://github.com/ktamas77/firebase-php/
     * @link   https://www.firebase.com/docs/rest-api.html
     *
     */

    /**
     * Firebase PHP Class
     *
     * @author Tamas Kalman <ktamas77@gmail.com>
     * @link   https://www.firebase.com/docs/rest-api.html
     *
     */
    class Firebase implements FirebaseInterface
    {
        private $_baseURI;
        private $_timeout;
        private $_token;

        /**
         * Constructor
         *
         * @param String $baseURI Base URI
         * @param String $token
         */
        function __construct($baseURI = '', $token = '')
        {
            if ($baseURI == '') {
                trigger_error('You must provide a baseURI variable.', E_USER_ERROR);
            }

            if (!extension_loaded('curl')) {
                trigger_error('Extension CURL is not loaded.', E_USER_ERROR);
            }

            $this->setBaseURI($baseURI);
            $this->setTimeOut(10);
            $this->setToken($token);
        }

        /**
         * Sets Base URI, ex: http://yourcompany.firebase.com/youruser
         *
         * @param String $baseURI Base URI
         *
         * @return void
         */
        public function setBaseURI($baseURI)
        {
            $baseURI .= (substr($baseURI, -1) == '/' ? '' : '/');
            $this->_baseURI = $baseURI;
        }

        /**
         * Sets REST call timeout in seconds
         *
         * @param Integer $seconds Seconds to timeout
         *
         * @return void
         */
        public function setTimeOut($seconds)
        {
            $this->_timeout = $seconds;
        }

        /**
         * Sets Token
         *
         * @param String $token Token
         *
         * @return void
         */
        public function setToken($token)
        {
            $this->_token = $token;
        }

        /**
         * Writing data into Firebase with a PUT request
         * HTTP 200: Ok
         *
         * @param String $path Path
         * @param Mixed  $data Data
         * @param bool   $async
         *
         * @return Array Response
         */
        public function set($path, $data, $async = false)
        {

            return $async ? $this->_writeAsync($path, $data, 'PUT') : $this->_writeData($path, $data, 'PUT');
        }

        private function _writeAsync($path, $data, $method = 'PUT')
        {
            $jsonData = json_encode($data, JSON_HEX_APOS);
            $cmd      = "curl -X " . $method . " -H 'Content-Type: application/json'";
            $cmd .= " -H 'Content-Length: " . strlen($jsonData) . "'";
            $cmd .= " -d '" . $jsonData . "' " . "'" . $this->_getJsonPath($path) . "'";
            $cmd .= " > /dev/null 2>&1 &";

            exec($cmd, $output, $exit);
            return $exit == 0;
        }

        /**
         * Returns with the normalized JSON absolute path
         *
         * @param String $path to data
         *
         * @return string
         */
        private function _getJsonPath($path)
        {
            $url  = $this->_baseURI;
            $path = ltrim($path, '/');
            $auth = ($this->_token == '') ? '' : '?auth=' . $this->_token;
            return $url . $path . '.json' . $auth;
        }

        private function _writeData($path, $data, $method = 'PUT')
        {
            $jsonData = json_encode($data);
            $header   = array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            );
            try {
                $ch = $this->_getCurlHandler($path, $method);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                $return = curl_exec($ch);
                curl_close($ch);
            } catch (Exception $e) {
                $return = null;
            }
            return $return;
        }

        /**
         * Returns with Initialized CURL Handler
         *
         * @param        $path
         * @param String $mode Mode
         *
         * @return resource
         */
        private function _getCurlHandler($path, $mode)
        {
            $url = $this->_getJsonPath($path);
            $ch  = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->_timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $mode);
            return $ch;
        }

        /**
         * Pushing data into Firebase with a POST request
         * HTTP 200: Ok
         *
         * @param String $path Path
         * @param Mixed  $data Data
         * @param bool   $async
         *
         * @return Array Response
         */
        public function push($path, $data, $async = false)
        {
            return $async ? $this->_writeAsync($path, $data, 'POST') : $this->_writeData($path, $data, 'POST');
        }

        /**
         * Updating data into Firebase with a PATH request
         * HTTP 200: Ok
         *
         * @param String $path Path
         * @param Mixed  $data Data
         * @param bool   $async
         *
         * @return Array Response
         */
        public function update($path, $data, $async = false)
        {
            return $async ? $this->_writeAsync($path, $data, 'PATCH') : $this->_writeData($path, $data, 'PATCH');
        }

        /**
         * Reading data from Firebase
         * HTTP 200: Ok
         *
         * @param String $path Path
         *
         * @return Array Response
         */
        public function get($path)
        {
            try {
                $ch     = $this->_getCurlHandler($path, 'GET');
                $return = curl_exec($ch);
                curl_close($ch);
            } catch (Exception $e) {
                $return = null;
            }
            return $return;
        }

        /**
         * Deletes data from Firebase
         * HTTP 204: Ok
         *
         * @param String $path Path
         * @param bool   $async
         *
         * @return Array Response
         */
        public function delete($path, $async = false)
        {
            if ($async) {
                $cmd = "curl -X DELETE '" . $this->_getJsonPath($path) . "'";
                $cmd .= " > /dev/null 2>&1 &";

                exec($cmd, $output, $exit);
                return $exit == 0;
            } else {
                try {
                    $ch     = $this->_getCurlHandler($path, 'DELETE');
                    $return = curl_exec($ch);
                    curl_close($ch);
                } catch (Exception $e) {
                    $return = null;
                }
                return $return;
            }
        }
    }
