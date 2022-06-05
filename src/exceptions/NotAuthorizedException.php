<?php


    namespace Auth\Exceptions;

    use Exception;
    use http\Message;

    class NotAuthorizedException extends Exception
    {
        public function __construct(string $message = "", int $code = 0, Exception $previous = null)
        {
            if(!$message) {
                $message = "You are not authorized to access this page.";
            }
            parent::__construct($message, $code, $previous);
        }

    }
