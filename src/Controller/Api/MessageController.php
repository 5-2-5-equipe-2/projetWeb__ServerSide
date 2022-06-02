<?php

    namespace Controllers;

    use Exception;
    use Models\MessageModel;

    class MessageController extends BaseController
    {
        public function listAction(){
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
                try {
                    $this->isRequestMethodOrThrow('GET');
                    $messageModel = new MessageModel();
                    $intLimit = 10;
                    list($queryArgs, $queryErrors) = self::getRequiredGetArgs(array('limit'), array('number'));
                    if (count($queryErrors) == 0) {
                        $intLimit = $queryArgs['limit'];
                    }
                    $arrMessages = $messageModel->getMessages($intLimit);
                    $responseData = json_encode($arrMessages);
                
                } catch (Exception $e) {
                    self::treatBasicExceptions($e);
                }
                self::sendData($strErrorDesc, $strErrorHeader, $responseData);
            }

        public function getByIdAction(){
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
                try {
                    $this->isRequestMethodOrThrow('GET');
                    $messageModel = new MessageModel();
                    list($queryArgs, $queryErrors) = self::getRequiredGetArgsorThrow(array('id'), array('number'));
                    $arrMessages = $messageModel->getMessages($queryArgs['id']);
                    $responseData = json_encode($arrMessages);
                
                } catch (Exception $e) {
                    self::treatBasicExceptions($e);
                }
                self::sendData($strErrorDesc, $strErrorHeader, $responseData);
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
                try {
                    $this->isRequestMethodOrThrow('POST');
                    $messageModel = new MessageModel();
                    list($queryArgs, $queryErrors) = self::getRequiredPostArgsorThrow(array('content','userId','chatRoomId'), array('string','string','number'));
                    $arrMessages = $messageModel->createMessage($queryArgs['content'],$queryArgs['userId'],$queryArgs['chatRoomId']);
                    $responseData = json_encode($arrMessages);
                
                } catch (Exception $e) {
                    self::treatBasicExceptions($e);
                }
                self::sendData($strErrorDesc, $strErrorHeader, $responseData);
            }

            public function searchMessagesAction(){
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
                try {
                    $this->isRequestMethodOrThrow('GET');
                    $messageModel = new MessageModel();
                    list($queryArgs, $queryErrors) = self::getRequiredGetArgsorThrow(array('query','limit'), array('string','number'));
                    $arrMessages = $messageModel->searchMessages($queryArgs['query'],$queryArgs['id']);
                    $responseData = json_encode($arrMessages);
                
                } catch (Exception $e) {
                    self::treatBasicExceptions($e);
                }
                self::sendData($strErrorDesc, $strErrorHeader, $responseData);
            }

            public function getMessagesInDateRangeAction(){
                $strErrorDesc = '';
                $responseData = array();
                $strErrorHeader = '';
                    try {
                        $this->isRequestMethodOrThrow('GET');
                        $messageModel = new MessageModel();
                        list($queryArgs, $queryErrors) = self::getRequiredGetArgsorThrow(array('startDat','endDate','limit'), array('string','string','number'));
                        $arrMessages = $messageModel->getMessagesInDateRange($queryArgs['startDat'],$queryArgs['endDate'],$queryArgs['limit']);
                        $responseData = json_encode($arrMessages);
                    
                    } catch (Exception $e) {
                        self::treatBasicExceptions($e);
                    }
                    self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                }
            
                public function modifyMessageAction(){
                    $strErrorDesc = '';
                    $responseData = array();
                    $strErrorHeader = '';
                        try {
                            $this->isRequestMethodOrThrow('PUT');
                            $messageModel = new MessageModel();
                            list($queryArgs, $queryErrors) = self::getRequiredPutArgsorThrow(array('msgId','content'), array('number','string'));
                            $arrMessages = $messageModel->modifyMessage($queryArgs['msgId'],$queryArgs['content']);
                            $responseData = json_encode($arrMessages);
                        
                        } catch (Exception $e) {
                            self::treatBasicExceptions($e);
                        }
                        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                    }
                
                    public function deleteUserMessagesAction(){
                        $strErrorDesc = '';
                        $responseData = array();
                        $strErrorHeader = '';
                            try {
                                $this->isRequestMethodOrThrow('PUT');
                                $messageModel = new MessageModel();
                                list($queryArgs, $queryErrors) = self::getRequiredPutArgsorThrow(array('userId'), array('number'));
                                $arrMessages = $messageModel->deleteUserMessages($queryArgs['userId']);
                                $responseData = json_encode($arrMessages);
                            
                            } catch (Exception $e) {
                                self::treatBasicExceptions($e);
                            }
                            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                        }
                    
                        public functiondeleteMessageAction(){
                            $strErrorDesc = '';
                            $responseData = array();
                            $strErrorHeader = '';
                                try {
                                    $this->isRequestMethodOrThrow('PUT');
                                    $messageModel = new MessageModel();
                                    list($queryArgs, $queryErrors) = self::getRequiredPutArgsorThrow(array('userId'), array('number'));
                                    $arrMessages = $messageModel->deleteUserMessages($queryArgs['userId']);
                                    $responseData = json_encode($arrMessages);
                                
                                } catch (Exception $e) {
                                    self::treatBasicExceptions($e);
                                }
                                self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                            }
    }