<?php

namespace Controllers;

use Auth\Exceptions\NotLoggedInException;
use Auth\Exceptions\WrongCredentialsException;
use Exception;
// use Managers\PixelManager;
use Models\NumberModel;

class NumberController extends BaseController
{
    protected function generateModel(): NumberModel
    {
        return new NumberModel();
    }

    public function guessNumberAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('GET');
            $numberModel = new NumberModel();
            $queryArgs = self::getRequiredGetArgsOrThrow(array('number','user_id'), array('number','number'));
            $arrNumbers = $numberModel->GuessNumber($queryArgs['number'], $queryArgs['user_id']);
            $responseData = json_encode($arrNumbers);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }
}
