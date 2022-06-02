<?php

namespace Controllers;

use Auth\Exceptions\NotLoggedInException;
use Auth\Exceptions\WrongCredentialsException;
use Exception;
use Managers\PixelManager;
use Models\PixelModel;

class PixelController extends BaseController
{
    protected function generateModel(): PixelModel
    {
        return new PixelModel();
    }

    public function getPixelsInRectangleAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('GET');
            $pixelModel = new PixelModel();
            $queryArgs = self::getRequiredGetArgsOrThrow(array('x1', 'y1', 'x2', 'y2'), array('number', 'number', 'number', 'number'));
            $arrPixels = $pixelModel->getPixelsInRectangle($queryArgs['x1'], $queryArgs['y1'], $queryArgs['x2'], $queryArgs['y2']);
            $responseData = json_encode($arrPixels);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function getPixelByIdAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('GET');
            $pixelModel = new PixelModel();
            $queryArgs = self::getRequiredGetArgsOrThrow(['id'], ['number']);
            $arrPixels = $pixelModel->getPixelById($queryArgs['id']);
            $responseData = json_encode($arrPixels);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function getPixelsInRectangleAfterDateAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('GET');
            $pixelModel = new PixelModel();
            $queryArgs = self::getRequiredGetArgsOrThrow(array('x1', 'y1', 'x2', 'y2', 'date'), array('number', 'number', 'number', 'number', 'date'));
            $arrPixels = $pixelModel->getPixelsInRectangleAfterDate($queryArgs['x1'], $queryArgs['y1'], $queryArgs['x2'], $queryArgs['y2'], $queryArgs['date']);
            $responseData = json_encode($arrPixels);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function updatePixelAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('PUT');
            $pixelModel = new PixelModel();
            $queryArgs = self::getRequiredPutArgsOrThrow(array('x', 'y', 'color_id', 'user_id'), array('number', 'number', 'number', 'number'));
            $arrPixels = $pixelModel->updatePixel($queryArgs['x'], $queryArgs['y'], $queryArgs['color_id'], $queryArgs['user_id']);
            $responseData = json_encode($arrPixels);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function deletePixelAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('PUT');
            $pixelModel = new PixelModel();
            $queryArgs = self::getRequiredPutArgsOrThrow(array('pixelId'), array('number'));
            $arrPixels = $pixelModel->deletePixel($queryArgs['pixelId']);
            $responseData = json_encode($arrPixels);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function createPixelAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('POST');
            $pixelModel = new PixelModel();

            $queryArgs = self::getRequiredPostArgsOrThrow(array('x', 'y', 'color_id', 'user_id'), array('number', 'number', 'number', 'number'));

            $arrPixels = $pixelModel->createPixel($queryArgs['x'], $queryArgs['y'], $queryArgs['color_id'], $queryArgs['user_id']);
            $responseData = json_encode($arrPixels);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function MakeUserIdNullAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('PUT');
            $pixelModel = new PixelModel();
            $queryArgs = self::getRequiredPutArgsOrThrow(array('pixelId'), array('number'));
            $arrPixels = $pixelModel->makeUserIdNull($queryArgs['pixelId']);
            $responseData = json_encode($arrPixels);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }
    public function getPixelsByUserIdAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {

            $this->isRequestMethodOrThrow('GET');
            $pixelModel = new PixelModel();
            $queryArgs =  $this->getRequiredGetArgsOrThrow(array("id"), array("number"));
            $arrPixels = $pixelModel->getPixelsByUserId($queryArgs['id']);
            $responseData = json_encode($arrPixels);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function getAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';

        try {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
            $parameters = explode("&", $uri);
            $art = [];
            foreach ($parameters as $value) {
                $s = explode("=", $value);
                $art[$s[0]] = $s[1];
            }

            $this->isRequestMethodOrThrow('GET');
            $pixelModel = new PixelModel();
            $arrPixels = $pixelModel->get($art);
            $responseData = json_encode($arrPixels);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }

        // send output
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }
}
