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
            try {
                $this->isRequestMethodOrThrow('GET');
                $chatRoomModel = new chatRoomModel();
                $intLimit = 10;
                list($queryArgs, $queryErrors) = self::getRequiredGetArgs(array('limit'), array('number'));
                if (count($queryErrors) == 0) {
                    $intLimit = $queryArgs['limit'];
                }

                $arrChatRoom = $chatRoomModel->getChatrooms($intLimit);
                $responseData = json_encode($arrChatRoom);
            } catch (Exception $e) {
                self::treatBasicExceptions($e);

            }
            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }


        /*
         * get chat room messages
         *
         *
         */
        public function getMessagesAction(){
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
                try {
                    $this->isRequestMethodOrThrow('GET');
                    $pixelModel = new chatRoomModel();
                    $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomId','limit'), array('number','number'));
                    $arrChatRooms = $pixelModel->getMessages($queryArgs['chatRoomId'], $queryArgs['limit']);
                    $responseData = json_encode($arrChatRooms);
                
                } catch (Exception $e) {
                    self::treatBasicExceptions($e);
                }
                self::sendData($strErrorDesc, $strErrorHeader, $responseData);
            }

            public function getUsersAction(){
                $strErrorDesc = '';
                $responseData = array();
                $strErrorHeader = '';
                    try {
                        $this->isRequestMethodOrThrow('GET');
                        $pixelModel = new chatRoomModel();
                        $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomId'), array('number'));
                        $arrChatRooms = $pixelModel->getUsers($queryArgs['chatRoomId']);
                        $responseData = json_encode($arrChatRooms);
                    
                    } catch (Exception $e) {
                        self::treatBasicExceptions($e);
                    }
                    self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                }
            
                public function getChatRoomByIdAction(){
                    $strErrorDesc = '';
                    $responseData = array();
                    $strErrorHeader = '';
                        try {
                            $this->isRequestMethodOrThrow('GET');
                            $pixelModel = new chatRoomModel();
                            $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomId'), array('number'));
                            $arrChatRooms = $pixelModel->getChatRoomById($queryArgs['chatRoomId']);
                            $responseData = json_encode($arrChatRooms);
                        
                        } catch (Exception $e) {
                            self::treatBasicExceptions($e);
                        }
                        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                    }

                    public function getChatRoomByNameAction(){
                        $strErrorDesc = '';
                        $responseData = array();
                        $strErrorHeader = '';
                            try {
                                $this->isRequestMethodOrThrow('GET');
                                $pixelModel = new chatRoomModel();
                                $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomName'), array('string'));
                                $arrChatRooms = $pixelModel->getChatRoomByName($queryArgs['chatRoomName']);
                                $responseData = json_encode($arrChatRooms);
                            
                            } catch (Exception $e) {
                                self::treatBasicExceptions($e);
                            }
                            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                        }

                        public function createChatRoomAction(){
                            $strErrorDesc = '';
                            $responseData = array();
                            $strErrorHeader = '';
                                try {
                                    $this->isRequestMethodOrThrow('GET');
                                    $pixelModel = new chatRoomModel();
                                    $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomName','ownerId'), array('string','number'));
                                    $arrChatRooms = $pixelModel->createChatRoom($queryArgs['chatRoomName'], $queryArgs['ownerId']);
                                    $responseData = json_encode($arrChatRooms);
                                
                                } catch (Exception $e) {
                                    self::treatBasicExceptions($e);
                                }
                                self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                            }

                            public function deleteUserFromChatRoomAction(){
                                $strErrorDesc = '';
                                $responseData = array();
                                $strErrorHeader = '';
                                    try {
                                        $this->isRequestMethodOrThrow('GET');
                                        $pixelModel = new chatRoomModel();
                                        $queryArgs= self::getRequiredGetArgsOrThrow(array('userId','chatRoomId'), array('number','number'));
                                        $arrChatRooms = $pixelModel->deleteUserFromChatRoom($queryArgs['userId'], $queryArgs['chatRoomId']);
                                        $responseData = json_encode($arrChatRooms);
                                    
                                    } catch (Exception $e) {
                                        self::treatBasicExceptions($e);
                                    }
                                    self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                                }

                                public function changeOwnerAction(){
                                    $strErrorDesc = '';
                                    $responseData = array();
                                    $strErrorHeader = '';
                                        try {
                                            $this->isRequestMethodOrThrow('GET');
                                            $pixelModel = new chatRoomModel();
                                            $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomId','newOwnerId'), array('number','number'));
                                            $arrChatRooms = $pixelModel->changeOwner($queryArgs['chatRoomId'], $queryArgs['newOwnerId']);
                                            $responseData = json_encode($arrChatRooms);
                                        
                                        } catch (Exception $e) {
                                            self::treatBasicExceptions($e);
                                        }
                                        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                                    }

                                    public function removeOwnerAction(){
                                        $strErrorDesc = '';
                                        $responseData = array();
                                        $strErrorHeader = '';
                                            try {
                                                $this->isRequestMethodOrThrow('GET');
                                                $pixelModel = new chatRoomModel();
                                                $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomId'), array('number'));
                                                $arrChatRooms = $pixelModel->removeOwner($queryArgs['chatRoomId']);
                                                $responseData = json_encode($arrChatRooms);
                                            
                                            } catch (Exception $e) {
                                                self::treatBasicExceptions($e);
                                            }
                                            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                                        }
    }