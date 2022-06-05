<?php


    namespace Auth\Exceptions;

    use Exception;

    class PermissionDoesNotExistException extends Exception
    {
        public function __construct(string $message = "", int $code = 0, Exception $previous = null)
        {
            if (!$message) {
                $message = "Permission does not exist.";
            }
            parent::__construct($message, 404);
        }
    }
