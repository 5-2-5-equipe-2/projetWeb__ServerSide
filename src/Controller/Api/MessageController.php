<?php

    namespace Controllers;

    use Exception;
    use Models\MessageModel;

    class MessageController extends BaseController
    {

        protected function generateModel(): MessageModel
        {
            return new MessageModel();
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
                    $queryArgs = $this->getRequiredGetArgsOrThrow(array('content','userId','chatRoomId'), array('string','number','number'));
                    $messageModel = new MessageModel();
                    $arrMessage = $messageModel->createMessage($queryArgs['content'], $queryArgs['userId'], $queryArgs['chatRoomId']);
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