<?php


    namespace Auth\Exceptions;

    use Exception;
    use http\Message;

    class AttributeDoesNotExistException extends Exception
    {
        public function __construct(string $message = "", int $code = 418, Exception $previous = null)
        {
            if(!$message) {
                $message = "Attribute Does Not Exist";
            }
            parent::__construct($message, $code, $previous);
        }

    }
