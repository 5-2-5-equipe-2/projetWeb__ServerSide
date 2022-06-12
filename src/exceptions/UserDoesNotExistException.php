<?php


    namespace Auth\Exceptions;

    use Exception;

    class UserDoesNotExistException extends Exception
    {
        public function __construct(string $message = "", int $code = 0, Exception $previous = null)
        {
            if (!$message) {
                $message = "User does not exist.";
            }
            parent::__construct($message, 404);
        }
    }
