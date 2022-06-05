<?php

namespace Auth\Exceptions;


class WrongSoluceGameException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Wrong Soluce !', 400);
    }
}
