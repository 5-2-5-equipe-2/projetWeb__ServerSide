<?php

    namespace Controllers;

    use Exception;
    use Models\ChatRoomModel;

    class ChatRoomController extends BaseController
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
                    $chatRoomModel = new ChatRoomModel();

                    $intLimit = 10;
                    if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                        $intLimit = $arrQueryStringParams['limit'];
                    }

                    $arrChatRoom = $chatRoomModel->getChatRooms($intLimit);
                    $responseData = json_encode($arrChatRoom);
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

        public function getAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getGETData();

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $chatRoomModel = new ChatRoomModel();

                    $chatRoomId = 10;
                    if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                        $chatRoomId = $arrQueryStringParams['id'];
                    }

                    $arrChatRoom = $chatRoomModel->getChatRoomById($chatRoomId);
                    $responseData = json_encode($arrChatRoom);
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

        /*
         * get chat room messages
         *
         *
         */
        public function getMessagesAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $requestMethod = $_SERVER["REQUEST_METHOD"];
            $arrQueryStringParams = $this->getGETData();

            if (strtoupper($requestMethod) == 'GET') {
                try {
                    $chatRoomModel = new ChatRoomModel();

                    $chatRoomId = null;
                    $limit = 10;
                    if (isset($arrQueryStringParams['id']) && $arrQueryStringParams['id']) {
                        if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                            $limit = $arrQueryStringParams['limit'];
                        }
                        $chatRoomId = $arrQueryStringParams['id'];
                    }

                    $arrChatRoom = $chatRoomModel->getMessages($chatRoomId, $limit);
                    $responseData = json_encode($arrChatRoom);
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

    }