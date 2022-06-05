<?php

    namespace Auth\Exceptions;

    use Exception;

    class PixelAlreadyExistException extends Exception
    {
        public function __construct($message = "", $code = 0, Exception $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }
