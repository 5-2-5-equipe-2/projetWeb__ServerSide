<?php

    namespace Controllers;

    use Auth\Exceptions\NotLoggedInException;
    use Auth\Exceptions\WrongCredentialsException;
    use Exception;
    use Managers\ColorManager;
    use Models\ColorModel;

    class ColorController extends BaseController
    {

        protected function generateModel(): ColorModel
        {
            return new ColorModel();
        }

        public function getColorByIdAction(){
            $strErrorDesc = '';
            $responseData = array();
            $strErrorHeader = '';
                try {
                    $this->isRequestMethodOrThrow('GET');
                    $colorModel = new ColorModel();
                    $queryArgs= self::getRequiredGetArgsOrThrow(array('Id'), array('number'));
                    $arrColors = $colorModel->getColorById($queryArgs['Id']);
                    $responseData = json_encode($arrColors);
                
                } catch (Exception $e) {
                    self::treatBasicExceptions($e);
                }
                self::sendData($strErrorDesc, $strErrorHeader, $responseData);
            }

            public function getColorByNameAction(){
                $strErrorDesc = '';
                $responseData = array();
                $strErrorHeader = '';
                    try {
                        $this->isRequestMethodOrThrow('GET');
                        $colorModel = new ColorModel();
                        $queryArgs= self::getRequiredGetArgsOrThrow(array('name'), array('string'));
                        $arrColors = $colorModel->getColorByName($queryArgs['name']);
                        $responseData = json_encode($arrColors);
                    
                    } catch (Exception $e) {
                        self::treatBasicExceptions($e);
                    }
                    self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                }

                public function getColorByHexCodeAction(){
                    $strErrorDesc = '';
                    $responseData = array();
                    $strErrorHeader = '';
                        try {
                            $this->isRequestMethodOrThrow('GET');
                            $colorModel = new ColorModel();
                            $queryArgs= self::getRequiredGetArgsOrThrow(array('hexcode'), array('string'));
                            $arrColors = $colorModel->getColorByHexCode($queryArgs['hexcode']);
                            $responseData = json_encode($arrColors);
                        
                        } catch (Exception $e) {
                            self::treatBasicExceptions($e);
                        }
                        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                    }
    
                    public function createColorAction(){
                        $strErrorDesc = '';
                        $responseData = array();
                        $strErrorHeader = '';
                            try {
                                $this->isRequestMethodOrThrow('POST');
                                $colorModel = new ColorModel();
                                $queryArgs= self::getRequiredPostArgsOrThrow(array('name','hexcode'), array('string','string'));
                                $arrColors = $colorModel->createColor($queryArgs['name'],$queryArgs['hexcode']);
                                $responseData = json_encode($arrColors);
                            
                            } catch (Exception $e) {
                                self::treatBasicExceptions($e);
                            }
                            self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                        }

                    
                        public function updateColorAction(){
                            $strErrorDesc = '';
                            $responseData = array();
                            $strErrorHeader = '';
                                try {
                                    $this->isRequestMethodOrThrow('PUT');
                                    $colorModel = new ColorModel();
                                    $queryArgs= self::getRequiredPutArgsOrThrow(array('id','name','hexcode'), array('number','string','string'));
                                    $arrColors = $colorModel->updateColor($queryArgs['id'],$queryArgs['name'],$queryArgs['hexcode']);
                                    $responseData = json_encode($arrColors);
                                
                                } catch (Exception $e) {
                                    self::treatBasicExceptions($e);
                                }
                                self::sendData($strErrorDesc, $strErrorHeader, $responseData);
                            }

                        
    }