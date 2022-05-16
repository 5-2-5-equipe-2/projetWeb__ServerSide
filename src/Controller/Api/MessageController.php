<?php

    namespace Controllers;

    use Exception;
    use Models\MessageModel;

    class MessageController extends BaseController
    {
        public function listAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getGETData();

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $messageModel = new MessageModel();
                    $intLimit = 10;
                    if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                        $intLimit = $arrQueryStringParams['limit'];
                    }
                    $arrMessage = $messageModel->getMessages($intLimit);
                    $responseData = json_encode($arrMessage);
                } catch (Exception $e) {
                    self::treatBasicExceptions($e);

                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            if (!$strErrorDesc) {
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else {
                $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                    array('Content-Type: application/json', $strErrorHeader)
                );
            }
        }

        public function getByIdAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getGETData();

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $messageModel = new MessageModel();
                    if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                        $messageId = $arrQueryStringParams['id'];
                    }
                    else{
                        throw new \InvalidArgumentException('Message id is required');
                    }

                    $arrMessage = $messageModel->getMessageById($messageId);
                    $responseData = json_encode($arrMessage);
                } catch (Exception $e) {
                    $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';

                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            if (!$strErrorDesc) {
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else {
                $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                    array('Content-Type: application/json', $strErrorHeader)
                );
            }
        }

        /**
         * create a new message
         *
         *
         */
        public function createAction(){
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getGETData();

            if (strtoupper($requestMethod) == 'POST') {
                try {
                    if (isset($arrQueryStringParams['userId']) && $arrQueryStringParams['userId']) {
                        $userId = $arrQueryStringParams['userId'];
                    }
                    else{
                        throw new \InvalidArgumentException('User id is required');
                    }
                    $messageModel = new MessageModel();
                    $arrMessage = $messageModel->createMessage($arrQueryStringParams);
                    $responseData = json_encode($arrMessage);
                } catch (Exception $e) {
                    self::treatBasicExceptions($e);
                }
            } else {
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
            }

            // send output
            if (!$strErrorDesc) {
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else {
                $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                    array('Content-Type: application/json', $strErrorHeader)
                );
            }
        }

    }