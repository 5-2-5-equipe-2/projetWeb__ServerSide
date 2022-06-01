<?php

    namespace Controllers;

    use Auth\Exceptions\NotLoggedInException;
    use Auth\Exceptions\WrongCredentialsException;
    use Exception;
    use Models\GameModel;

    class GameController extends BaseController
    {
        /**
         * "/game/getRandom" Endpoint - Get a random game
         */
        public function getRandomAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
            $arrQueryStringParams = $this->getGETData();

            try {
                $this->isRequestMethodOrThrow('GET');
                $gameModel = new GameModel();
                $game_selected = $gameModel->getGameForPlayer($arrQueryStringParams['user_id']);
                $responseData = json_encode($game_selected);
            } catch (Exception $e) {
                self::treatBasicExceptions($e);

            }
            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

         /**
         * "/game/getAll" Endpoint - Get list of games
         */
        public function getAllAction()
        {
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';

            try {
                $this->isRequestMethodOrThrow('GET');
                $gameModel = new GameModel();
                $game_selected = $gameModel->getGameList();
                $responseData = json_encode($game_selected);

            } catch (Exception $e) {
                self::treatBasicExceptions($e);

            }
            // send output
            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
        }

        
    }
