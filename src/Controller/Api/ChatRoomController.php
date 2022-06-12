<?php

    namespace Controllers;

    use Exception;
    use Models\ChatRoomModel;

    class ChatRoomController extends BaseController
    {

        protected function generateModel(): ChatRoomModel
        {
            return new ChatRoomModel();
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
            try {
                $this->isRequestMethodOrThrow('GET');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredGetArgsOrThrow(array('chatRoomId', 'offset', 'limit'), array('number', 'number', 'number'));
                $arrChatRooms = $chatRoomModel->getMessages($queryArgs['chatRoomId'], $queryArgs['offset'], $queryArgs['limit']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function getUsersAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('GET');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredGetArgsOrThrow(array('chatRoomId'), array('number'));
                $arrChatRooms = $chatRoomModel->getUsers($queryArgs['chatRoomId']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function getChatRoomByIdAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('GET');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredGetArgsOrThrow(array('chatRoomId'), array('number'));
                $arrChatRooms = $chatRoomModel->getChatRoomById($queryArgs['chatRoomId']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function deleteChatRoomAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('PUT');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredPutArgsOrThrow(array('chatRoomId'), array('number'));
                $arrChatRooms = $chatRoomModel->deleteChatRoom($queryArgs['chatRoomId']);
                $responseData = json_encode($arrChatRooms);
            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }


        public function getChatRoomByNameAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('GET');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredGetArgsOrThrow(array('chatRoomName'), array('string'));
                $arrChatRooms = $chatRoomModel->getChatRoomByName($queryArgs['chatRoomName']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function createChatRoomAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('POST');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredPostArgsOrThrow(array('name', 'ownerId', 'isPrivate'), array('string', 'number', 'number'));
                $queryArgs2= self::getRequiredPostArgs(array('description', 'profile_picture'), array('string','string'));
                isset($queryArgs2[0]['description']) ? $queryArgs['description'] = $queryArgs2[0]['description'] : $queryArgs['description'] = null;
                isset($queryArgs2[0]['profile_picture']) ? $queryArgs['profile_picture'] = $queryArgs2[0]['profile_picture'] : $queryArgs['profile_picture'] = null;
                $arrChatRooms = $chatRoomModel->createChatRoom($queryArgs['name'], $queryArgs['ownerId'], $queryArgs['description'], $queryArgs['isPrivate'], $queryArgs['profile_picture']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function deleteUserFromChatRoomAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('PUT');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredPutArgsOrThrow(array('userId', 'chatRoomId'), array('number', 'number'));
                $arrChatRooms = $chatRoomModel->deleteUserFromChatRoom($queryArgs['userId'], $queryArgs['chatRoomId']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function changeOwnerAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('PUT');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredPutArgsOrThrow(array('chatRoomId', 'newOwnerId'), array('number', 'number'));
                $arrChatRooms = $chatRoomModel->changeOwner($queryArgs['chatRoomId'], $queryArgs['newOwnerId']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function addUserToChatRoomAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('PUT');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredPutArgsOrThrow(array('chatRoomId', 'userId'), array('number', 'number'));
                $arrChatRooms = $chatRoomModel->addUserToChatRoom($queryArgs['chatRoomId'], $queryArgs['userId']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function searchPublicChatRoomAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('GET');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredGetArgsOrThrow(array('query'), array('string'));
                $queryArgs2= self::getRequiredGetArgs(array('limit'), array('number'));
                isset($queryArgs2[0]['limit']) ? $queryArgs['limit'] = $queryArgs2[0]['limit'] : $queryArgs['limit'] = 10;
                $arrChatRooms = $chatRoomModel->searchPublicChatRoom($queryArgs['query'],$queryArgs['limit']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        public function updateUsersActions()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            try {
                $this->isRequestMethodOrThrow('PUT');
                $chatRoomModel = new chatRoomModel();
                $queryArgs = self::getRequiredPutArgsOrThrow(array('chatRoomId', 'usersId'), array('number', 'number'));
                $arrChatRooms = $chatRoomModel->updateUsers($queryArgs['chatRoomId'], $queryArgs['usersId']);
                $responseData = json_encode($arrChatRooms);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);
            }
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }
        
    }