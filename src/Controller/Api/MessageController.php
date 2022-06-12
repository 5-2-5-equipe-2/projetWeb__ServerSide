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
    public function createMessageAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('POST');
            $messageModel = new MessageModel();
            $queryArgs = self::getRequiredPostArgsOrThrow(array('content', 'userId', 'chatRoomId'), array('string', 'number', 'number'));
            $arrMessages = $messageModel->createMessage($queryArgs['content'], $queryArgs['userId'], $queryArgs['chatRoomId']);
            $responseData = json_encode($arrMessages);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function searchMessagesAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('GET');
            $messageModel = new MessageModel();
            $queryArgs = self::getRequiredGetArgsorThrow(array('query', 'limit'), array('string', 'number'));
            $arrMessages = $messageModel->searchMessages($queryArgs['query'], $queryArgs['limit']);
            $responseData = json_encode($arrMessages);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function getMessagesInDateRangeAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('GET');
            $messageModel = new MessageModel();
            $queryArgs = self::getRequiredGetArgsorThrow(array('startDat', 'endDate', 'limit'), array('date', 'date', 'number'));
            $arrMessages = $messageModel->getMessagesInDateRange($queryArgs['startDat'], $queryArgs['endDate'], $queryArgs['limit']);
            $responseData = json_encode($arrMessages);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function modifyMessageAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('PUT');
            $messageModel = new MessageModel();
            $queryArgs = self::getRequiredPutArgsorThrow(array('msgId', 'content'), array('number', 'string'));
            $arrMessages = $messageModel->modifyMessage($queryArgs['msgId'], $queryArgs['content']);
            $responseData = json_encode($arrMessages);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function deleteUserMessagesAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('PUT');
            $messageModel = new MessageModel();
            $queryArgs = self::getRequiredPutArgsorThrow(array('userId'), array('number'));
            $arrMessages = $messageModel->deleteUserMessages($queryArgs['userId']);
            $responseData = json_encode($arrMessages);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function deleteMessageAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('PUT');
            $messageModel = new MessageModel();
            $queryArgs = self::getRequiredPutArgsorThrow(array('msgId'), array('number'));
            $arrMessages = $messageModel->deleteUserMessages($queryArgs['msgId']);
            $responseData = json_encode($arrMessages);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }
}
