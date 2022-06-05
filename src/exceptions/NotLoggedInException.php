<?php

    namespace Auth\Exceptions;

    use Exception;

    class NotLoggedInException extends Exception
    {
        public function __construct(string $message = "", int $code = 422, Exception $previous = null)
        {
            if(!$message) {
                $message = "User not logged in";
            }
            parent::__construct($message, $code, $previous);
        }
    }
