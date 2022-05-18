<?php

    namespace Controllers;

    use Auth\Exceptions\NotLoggedInException;
    use Auth\Exceptions\WrongCredentialsException;
    use Exception;
    use Managers\UserManager;
    use Models\UserModel;

    class UserController extends BaseController
    {
        /**
         * "/user/list" Endpoint - Get list of users
         */
        public function listAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('GET');
                $userModel = new UserModel();
                $intLimit = 10;
                list($queryArgs, $queryErrors) = self::getRequiredGetArgs(array('limit'), array('number'));
                if (count($queryErrors) == 0) {
                    $intLimit = $queryArgs['limit'];
                }

                $arrUsers = $userModel->getUsers($intLimit);
                $responseData = json_encode($arrUsers);
            } catch (Exception $e) {
                self::treatBasicExceptions($e);

            }
            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function getAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';

            try {
                $this->isRequestMethodOrThrow('GET');
                $userModel = new UserModel();
                $queryArgs = $this->getRequiredGetArgsOrThrow(array('id'), array('number'));
                $userId = $queryArgs['id'];
                $arrUsers = $userModel->getUserById($userId);
                $responseData = json_encode($arrUsers);
            } catch (Exception $e) {
                self::treatBasicExceptions($e);

            }

            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function getByUsernameAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {

                $this->isRequestMethodOrThrow('GET');
                $userModel = new UserModel();
                $queryArgs = $this->getRequiredGetArgsOrThrow(array('username'), array('string'));
                $userUsername = $queryArgs['username'];
                $user = $userModel->getUserByUsername($userUsername);
                $responseData = json_encode($user);


            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public  function searchAction(){
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('GET');
                $userModel = new UserModel();
                $queryArgs = $this->getRequiredGetArgsOrThrow(array('search'), array('string'));
                $search = $queryArgs['search'];
                $users = $userModel->searchUser($search);
                $responseData = json_encode($users);
            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }


        public function getByEmailAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getGETData();

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $userModel = new UserModel();

                    if (isset($arrQueryStringParams['email']) && $arrQueryStringParams['email']) {
                        $userEmail = $arrQueryStringParams['email'];
                        $user = $userModel->getUserByEmail($userEmail);
                        $responseData = json_encode($user);

                    } else {
                        $strErrorDesc = 'Arguments missing or invalid';
                        $strErrorHeader = 'HTTP/1.1 418 Bad Request';
                    }

                } catch (Exception $e) {
                    self::treatBasicExceptions($e);

                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function getMessagesAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getGETData();

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $userModel = new UserModel();

                    if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                        $userId = $arrQueryStringParams['id'];
                        $arrMessages = $userModel->getMessages($userId);
                        $responseData = json_encode($arrMessages);
                    } else {
                        $strErrorDesc = 'Arguments missing or invalid';
                        $strErrorHeader = 'HTTP/1.1 418 Bad Request';
                    }

                } catch (Exception $e) {
                    self::treatBasicExceptions($e);

                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function getChatRoomsAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getGETData();

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $userModel = new UserModel();

                    if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                        $userId = $arrQueryStringParams['id'];
                        $arrChatRooms = $userModel->getChatRooms($userId);
                        $responseData = json_encode($arrChatRooms);

                    } else {
                        $strErrorDesc = 'Arguments missing or invalid';
                        $strErrorHeader = 'HTTP/1.1 418 Bad Request';
                    }

                } catch (Exception $e) {
                    self::treatBasicExceptions($e);
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        /**
         * Create a new User with POST method
         */
        public function createUserAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getPOSTData();

            if (strtoupper($requestMethod) == 'POST') {
                try {
                    $userManager = new UserManager();
                    $userManager->createUser($arrQueryStringParams['username'], $arrQueryStringParams['password']);
                    $responseData = json_encode(array('success' => 'User created'));


                } catch (Exception $e) {
                    self::treatBasicExceptions($e);

                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        /**
         * Login the user with POST method
         *
         */
        public function loginAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $postData = $this->getPOSTData();
            if (strtoupper($requestMethod) == 'POST') {
                try {
                    $userManager = new UserManager();

                    if (isset($postData['username']) && isset($postData['password'])) {
                        $userManager->login($postData['username'], $postData['password']);
                        $responseData = json_encode(array('success' => 'User logged in'));
                    } else {
                        $strErrorDesc = 'Arguments missing or invalid';
                        $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                    }

                } catch (Exception $e) {
                    self::treatBasicExceptions($e);

                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        /**
         * Logout the user with POST method
         *
         */

        public function logoutAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];

            if (strtoupper($requestMethod) == 'POST') {
                try {
                    $userManager = new UserManager();
                    $userManager->logout();
                    $responseData = json_encode(array('success' => 'User logged out'));


                } catch (Exception $e) {
                    self::treatBasicExceptions($e);
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        /**
         * Update the user with PUT method
         *
         */
        public function updateAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];

            if (strtoupper($requestMethod) == 'PUT') {
                try {
                    $userManager = new UserManager();
                    try {
                        #TODO update user
                        $userManager->updateUser($this->getPOSTData());
                        $responseData = json_encode(array('success' => 'User updated'));

                    } catch (Exception $e) {
                        $strErrorDesc = 'Arguments missing or invalid' . $e->getMessage();
                        $strErrorHeader = 'HTTP/1.1 418 Bad Request';
                    }

                } catch (Exception $e) {
                    $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';

                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

    }
