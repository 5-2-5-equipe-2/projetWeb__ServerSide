<?php

namespace Controllers;

use Auth\Exceptions\InvalidArgumentException;
use Auth\Exceptions\UnprocessableEntityException;
use Exception;
use Models\Database;
use JetBrains\PhpStorm\NoReturn;

abstract class BaseController
{
    var $MODEL;

    /**
     * 
     */
    public function __construct()
    {
        $this->MODEL = $this->generateModel();
    }

    abstract protected function generateModel(): Database;

    function getModel()
    {
        return $this->MODEL;
    }

    /**
     * __call magic method.
     */
    #[NoReturn] public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Get URI elements.
     *
     * @return array
     */
    protected function getUriSegments(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return explode('/', $uri);
    }

    /**
     * Get querystring params.
     *
     */
    protected function getGETData(): array
    {
        $query = array();
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }

    /**
     * Get post params.
     */
    protected function getPOSTData(): array
    {
        $data = file_get_contents('php://input');
        if ($data) {
            return json_decode($data, true);
        } else {
            return array();
        }
    }

    /**
     * Get PUT params.
     */
    protected function getPUTData(): array
    {
        $data = file_get_contents('php://input');
        if ($data) {
            return json_decode($data, true);
        } else {
            return array();
        }
    }


    /**
     * Send API output.
     *
     * @param string $data
     * @param array $httpHeaders
     */
    #[NoReturn] protected function sendOutput(string $data, array $httpHeaders = array()): void
    {

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        echo $data;

        exit;
    }

    /**
     * @param Exception $e
     * @return void
     */
    #[NoReturn] protected function treatBasicExceptions(Exception $e): void
    {
        if ($e->getMessage()) {
            $strErrorDesc = $e->getMessage();
        } else {
            $strErrorDesc = 'Something went wrong! Please contact support.';
        }
        if ($e->getCode()) {
            switch ($e->getCode()) {
                case 422: {
                        $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
                        break;
                    }
                default: {
                        $strErrorHeader = 'HTTP/1.1 ' . $e->getCode() . $e->getPrevious() . 'Error';
                        break;
                    }
            }
        } else {
            $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }
        $this->sendOutput(
            json_encode(array('error' => $strErrorDesc)),
            array('Content-Type: application/json', $strErrorHeader)
        );
    }


    /**
     * @param string $strErrorDesc
     * @param string $strErrorHeader
     * @param string $responseData
     * @return void
     */
    #[NoReturn] protected function sendData(string $strErrorDesc, string $strErrorHeader, string $responseData): void
    {
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(
                json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /**
     * get type from string
     *
     * @param string $data
     * @return string
     **/
    protected function getTypeFromString(string $data): string
    {
        if (strtotime($data)) return "date";

        // if $data is not a valid date format strtotime() will return false
        // so we're just using it as a validator basically
        if (is_numeric($data)) return "number";

        return 'string';
    }


    /**
     * get required args
     *
     */
    protected function getRequiredArgs(array $arrRequiredArgs, array $arrRequestData, array $types): array
    {
        $arrMissingArgs = array();
        $i = 0;
        foreach ($arrRequiredArgs as $requiredArg) {
            if (!isset($arrRequestData[$requiredArg]) || $arrRequestData[$requiredArg] == '' || self::getTypeFromString($arrRequestData[$requiredArg]) != $types[$i]) {
                $arrMissingArgs[] = $requiredArg;
            }
            $i++;
        }
        return array($arrRequestData, $arrMissingArgs);
    }

    /**
     * check args
     *
     */
    protected function checkArgs(array $arrRequiredArgs, array $arrRequestData, array $types): bool
    {
        $arr = $this->getRequiredArgs($arrRequiredArgs, $arrRequestData, $types);
        return strlen($arr[1]) == 0;
    }


    /**
     * get required post args
     *
     */
    protected function getRequiredPostArgs(array $arrRequiredArgs, array $types): array
    {
        $arrPostData = $this->getPOSTData();
        return $this->getRequiredArgs($arrRequiredArgs, $arrPostData, $types);
    }

    /**
     * get required post args and throws errors
     *
     * @throws InvalidArgumentException
     */
    protected function getRequiredPostArgsOrThrow(array $arrRequiredArgs, array $types): array
    {
        $data = $this->getRequiredPostArgs($arrRequiredArgs, $types);
        if (count($data[1])) {
            throw new InvalidArgumentException(
                'Missing or invalid required arguments: ' . implode(', ', $data[1]),
                418
            );
        }
        return $data[0];
    }

    protected function getRequiredPutArgs(array $arrRequiredArgs, array $types): array
    {
        $arrPostData = $this->getPUTData();
        return $this->getRequiredArgs($arrRequiredArgs, $arrPostData, $types);
    }

    /**
     * get required post args and throws errors
     *
     * @throws InvalidArgumentException
     */
    protected function getRequiredPutArgsOrThrow(array $arrRequiredArgs, array $types): array
    {
        $data = $this->getRequiredPostArgs($arrRequiredArgs, $types);
        if (count($data[1])) {
            throw new InvalidArgumentException(
                'Missing or invalid required arguments: ' . implode(', ', $data[1]),
                418
            );
        }
        return $data[0];
    }

    /**
     * get the required get args
     *
     * @param array $arrRequiredArgs
     * @param array $types
     * @return array
     */
    protected function getRequiredGetArgs(array $arrRequiredArgs, array $types): array
    {
        $arrGetData = $this->getGETData();
        return $this->getRequiredArgs($arrRequiredArgs, $arrGetData, $types);
    }

    /**
     * get required get args and throws errors
     * @param array $arrRequiredArgs
     * @param array $types
     * @return array
     * @throws InvalidArgumentException
     */
    protected function getRequiredGetArgsOrThrow(array $arrRequiredArgs, array $types): array
    {
        $data = $this->getRequiredGetArgs($arrRequiredArgs, $types);
        if (count($data[1]) > 0) {
            throw new InvalidArgumentException(
                'Missing or invalid required arguments: ' . implode(', ', $data[1]),
                418
            );
        }
        return $data[0];
    }

    /**
     * Check if the server request method is $method
     *
     * @param $requestMethod string method of the request
     * @throws UnprocessableEntityException
     */
    protected function isRequestMethodOrThrow(string $requestMethod): void
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) != $requestMethod) {
            throw new UnprocessableEntityException();
        }
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
            $limit=50;
            foreach ($parameters as $value) {
                $s = explode("=", $value);
                if (strcmp($s[0], "limit") == 0) {
                    $limit = $s[1];
                } else {
                    $art[$s[0]] = $s[1];
                }
            }

            $this->isRequestMethodOrThrow('GET');
            $model = $this->getModel();
            $arrUsers = $model->get($art, $limit);
            $responseData = json_encode($arrUsers);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }

        // send output
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }

    /**
     * "/?/list" Endpoint - Get list of ?
     */
    public function listAction()
    {
        $strErrorDesc = '';
        $responseData = array();
        $strErrorHeader = '';
        try {
            $this->isRequestMethodOrThrow('GET');
            $Model = $this->getModel();
            $intLimit = 10;
            list($queryArgs, $queryErrors) = self::getRequiredGetArgs(array('limit'), array('number'));
            if (count($queryErrors) == 0) {
                $intLimit = $queryArgs['limit'];
            }
            else {
                $intLimit = 50;
            }
            $arr = $Model->get([], $intLimit);
            $responseData = json_encode($arr);
        } catch (Exception $e) {
            self::treatBasicExceptions($e);
        }
        self::sendData($strErrorDesc, $strErrorHeader, $responseData);
    }
}
