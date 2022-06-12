<?php

namespace Controllers;

use Exception;
use Models\ColorModel;

class ColorController extends BaseController
{

    protected function generateModel(): ColorModel
    {
        return new ColorModel();
    }

    public function createColorAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('POST');
            $colorModel = new ColorModel();
            $queryArgs = self::getRequiredPostArgsOrThrow(array('name', 'hexcode'), array('string', 'string'));
            $arrColors = $colorModel->createColor($queryArgs['name'], $queryArgs['hexcode']);
            $responseData = json_encode($arrColors);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    public function updateColorAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('PUT');
            $colorModel = new ColorModel();
            $queryArgs = self::getRequiredPutArgsOrThrow(array('id', 'name', 'hexcode'), array('number', 'string', 'string'));
            $arrColors = $colorModel->updateColor($queryArgs['id'], $queryArgs['name'], $queryArgs['hexcode']);
            $responseData = json_encode($arrColors);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }
}
