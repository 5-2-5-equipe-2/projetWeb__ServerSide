<?php

    namespace Controllers;

    use Exception;
    use Models\ChatRoomModel;

    class ChatRoomController extends BaseController
    {

        protected function generateModel() : ChatRoomModel
        {
            return new ChatRoomModel();
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
                    $chatRoomModel = new chatRoomModel();
                    $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomId','offset','limit'), array('number','number','number'));
                    $arrChatRooms = $chatRoomModel->getMessages($queryArgs['chatRoomId'], $queryArgs['offset'],$queryArgs['limit']);
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
                        $chatRoomModel = new chatRoomModel();
                        $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomId'), array('number'));
                        $arrChatRooms = $chatRoomModel->getUsers($queryArgs['chatRoomId']);
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
                            $chatRoomModel = new chatRoomModel();
                            $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomId'), array('number'));
                            $arrChatRooms = $chatRoomModel->getChatRoomById($queryArgs['chatRoomId']);
                            $responseData = json_encode($arrChatRooms);
                        
                        } catch (Exception $e) {
                            self::treatBasicExceptions($e);
                        }
                        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                    }

                    public function deleteChatRoomAction(){
                        $strErrorDesc = '';
                        $responseData = array();
                        $strErrorHeader = '';
                            try {
                                $this->isRequestMethodOrThrow('PUT');
                                $chatRoomModel = new chatRoomModel();
                                $queryArgs= self::getRequiredPutArgsOrThrow(array('chatRoomId'), array('number'));
                                $arrChatRooms = $chatRoomModel->deleteChatRoom($queryArgs['chatRoomId']);
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
                                $chatRoomModel = new chatRoomModel();
                                $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomName'), array('string'));
                                $arrChatRooms = $chatRoomModel->getChatRoomByName($queryArgs['chatRoomName']);
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
                                    $chatRoomModel = new chatRoomModel();
                                    $queryArgs= self::getRequiredGetArgsOrThrow(array('chatRoomName','ownerId'), array('string','number'));
                                    $arrChatRooms = $chatRoomModel->createChatRoom($queryArgs['chatRoomName'], $queryArgs['ownerId']);
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
                                        $this->isRequestMethodOrThrow('PUT');
                                        $chatRoomModel = new chatRoomModel();
                                        $queryArgs= self::getRequiredPutArgsOrThrow(array('userId','chatRoomId'), array('number','number'));
                                        $arrChatRooms = $chatRoomModel->deleteUserFromChatRoom($queryArgs['userId'], $queryArgs['chatRoomId']);
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
                                            $this->isRequestMethodOrThrow('PUT');
                                            $chatRoomModel = new chatRoomModel();
                                            $queryArgs= self::getRequiredPutArgsOrThrow(array('chatRoomId','newOwnerId'), array('number','number'));
                                            $arrChatRooms = $chatRoomModel->changeOwner($queryArgs['chatRoomId'], $queryArgs['newOwnerId']);
                                            $responseData = json_encode($arrChatRooms);
                                        
                                        } catch (Exception $e) {
                                            self::treatBasicExceptions($e);
                                        }
                                        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                                    }


    }