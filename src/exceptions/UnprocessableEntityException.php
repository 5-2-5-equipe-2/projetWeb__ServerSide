<?php


    namespace Auth\Exceptions;

    use Exception;
    use http\Message;

    class UnprocessableEntityException extends Exception
    {
        public function __construct(string $message = "", int $code = 422, Exception $previous = null)
        {
            if(!$message) {
                $message = "Method not supported";
            }
            parent::__construct($message, $code, $previous);
        }

    }
