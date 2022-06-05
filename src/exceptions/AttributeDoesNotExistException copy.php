<?php


    namespace Auth\Exceptions;

    use Exception;
    use http\Message;

    class ElementDoesNotExistException extends Exception
    {
        public function __construct(string $message = "", int $code = 418, Exception $previous = null)
        {
            if(!$message) {
                $message = "Element Does Not Exist";
            }
            parent::__construct($message, $code, $previous);
        }

    }
