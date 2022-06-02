<?php

namespace Auth\Exceptions;


class WrongCredentialsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Wrong credentials', 401);
    }
}
