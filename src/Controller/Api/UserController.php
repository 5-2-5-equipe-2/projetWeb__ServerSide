<?php

namespace Controllers;

use Auth\Exceptions\NotLoggedInException;
use Auth\Exceptions\WrongCredentialsException;
use Exception;
use Managers\UserManager;
use Models\UserModel;

class UserController extends BaseController
{

    protected function generateModel(): UserModel
    {
        return new UserModel();
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

    public function searchAction()
    {
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
                                try {
                                    $this->isRequestMethodOrThrow('POST');
                                    $userModel = new userModel();
                                    $queryArgs= self::getRequiredPostArgsOrThrow(array('username','password'), array('string','string'));
                                    $queryArgs2= self::getRequiredPostArgs(array('first_name, surname, email, profile_picture'), array('string','string','string','string'));
                                    isset($queryArgs2[0]['first_name']) ? $queryArgs['first_name'] = $queryArgs2[0]['first_name'] : $queryArgs['first_name'] = null;
                                    isset($queryArgs2[0]['surname']) ? $queryArgs['surname'] = $queryArgs2[0]['surname'] : $queryArgs['surname'] = null;
                                    isset($queryArgs2[0]['email']) ? $queryArgs['email'] = $queryArgs2[0]['email'] : $queryArgs['email'] = null;
                                    isset($queryArgs2[0]['profile_picture']) ? $queryArgs['profile_picture'] = $queryArgs2[0]['profile_picture'] : $queryArgs['profile_picture'] = null;





                                    
                                    $arrUsers = $userModel->createuser($queryArgs['username'],$queryArgs['password'],$queryArgs['first_name'],$queryArgs['surname'],$queryArgs['email'],$queryArgs['profile_picture']);
                                    $responseData = json_encode($arrUsers);
                                
                                } catch (Exception $e) {
                                    self::treatBasicExceptions($e);
                                }
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
        $responseData = "";
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

    public function updatePassAction()
    {
        $strErrorDesc = '';
        $responseData = "";
        $strErrorHeader = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'PUT') {
            try {
                $userManager = new UserManager();
                try {
                    $userManager->updatePassword($this->getPOSTData());
                    $responseData = json_encode(array('success' => 'Password updated'));
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

    public function deleteUserAction()
    {
        $strErrorDesc = '';
        $responseData = "";
        $strErrorHeader = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'PUT') {
            try {
                $userManager = new UserManager();
                try {
                    $userManager->deleteUser($this->getPOSTData());
                    $responseData = json_encode(array('success' => 'User deleted'));
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

    /**
     * Get the currently logged user
     *
     */
    public function getCurrentlyLoggedInUserAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userManager = new UserManager();
                $userModel = new UserModel();
                $userId = $userManager->getLoggedInUserId();
                $userData = $userModel->getUserById($userId);
                $responseData = json_encode($userData);
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
}
