<?php
    namespace Firebase;

    /**
     * Class FirebaseStub
     *
     * Stubs the Firebase interface without issuing any cURL requests.
     *
     * @package Firebase
     */
    class FirebaseStub implements FirebaseInterface
    {
        /**
         * @var
         */
        public $_baseURI;
        /**
         * @var
         */
        public $_token;
        /**
         * @var null
         */
        private $_response = null;

        /**
         * @param string $baseURI
         * @param string $token
         */
        function __construct($baseURI = '', $token = '')
        {
            if (!extension_loaded('curl')) {
                trigger_error('Extension CURL is not loaded.', E_USER_ERROR);
            }

            $this->setBaseURI($baseURI);
            $this->setTimeOut(10);
            $this->setToken($token);
        }

        /**
         * @param $baseURI
         *
         * @return null
         */
        public function setBaseURI($baseURI)
        {
            $baseURI .= (substr($baseURI, -1) == '/' ? '' : '/');
            $this->_baseURI = $baseURI;
        }

        /**
         * @param $seconds
         *
         * @return null
         */
        public function setTimeOut($seconds)
        {
            $this->_timeout = $seconds;
        }

        /**
         * @param $token
         *
         * @return null
         */
        public function setToken($token)
        {
            $this->_token = $token;
        }

        /**
         * @param      $path
         * @param      $data
         * @param bool $async
         *
         * @return null
         */
        public function push($path, $data, $async = false)
        {
            return $this->set($path, $data);
        }

        /**
         * @param      $path
         * @param      $data
         * @param bool $async
         *
         * @return null
         */
        public function set($path, $data, $async = false)
        {
            return $this->_getSetResponse($data);
        }

        /**
         * @param $data
         *
         * @return null
         */
        private function _getSetResponse($data)
        {
            $validBaseUriObject = $this->_isBaseURIValid();
            if ($validBaseUriObject->error) {
                return $validBaseUriObject->message;
            }

            $validDataObject = $this->_isDataValid($data);
            if ($validDataObject->error) {
                return $validDataObject->message;
            }

            return $this->_response;
        }

        /**
         * @uses $this->_baseURI
         * @return Error
         */
        private function _isBaseURIValid()
        {
            $error = preg_match('/^https:\/\//', $this->_baseURI);
            return new Error(($error == 0 ? true : false), 'Firebase does not support non-ssl traffic. Please try your request again over https.');
        }

        /**
         * @param $data
         *
         * @return Error
         */
        private function _isDataValid($data)
        {

            if ($data == "" || $data == null) {
                return new Error(true, "Missing data; Perhaps you forgot to send the data.");
            }

            $error = json_decode($data);
            return new Error(($error ? false : true), "Invalid data; couldn't parse JSON object, array, or value. Perhaps you're using invalid characters in your key names.");
        }

        /**
         * @param      $path
         * @param      $data
         * @param bool $async
         *
         * @return null
         */
        public function update($path, $data, $async = false)
        {
            return $this->set($path, $data);
        }

        /**
         * @param $path
         *
         * @return null
         */
        public function get($path)
        {
            return $this->_getGetResponse();
        }

        /**
         * @return null
         */
        private function _getGetResponse()
        {
            $validBaseUriObject = $this->_isBaseURIValid();
            if ($validBaseUriObject->error) {
                return $validBaseUriObject->message;
            }
            return $this->_response;
        }

        /**
         * @param      $path
         * @param bool $async
         *
         * @return null
         */
        public function delete($path, $async = false)
        {
            return $this->_getDeleteResponse();
        }

        /**
         * @return null
         */
        private function _getDeleteResponse()
        {
            return $this->_getGetResponse();
        }

        /**
         * @param $expectedResponse
         */
        public function setResponse($expectedResponse)
        {
            $this->_response = $expectedResponse;
        }
    }

    /**
     * Class Error
     *
     * @package Firebase
     */
    class Error
    {
        /**
         * @param $error
         * @param $message
         */
        function __construct($error, $message)
        {
            $this->error   = $error;
            $this->message = $message;
        }
    }
