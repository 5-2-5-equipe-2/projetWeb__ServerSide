<?php

namespace Auth\Exceptions;


class TimeoutSoluceGameException extends \Exception
{
    public function __construct()
    {
        parent::__construct('It\'s too late !', 400);
    }
}
