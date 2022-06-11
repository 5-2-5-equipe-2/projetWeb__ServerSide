<?php

namespace Auth\Exceptions;


class NoGamePlayableException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No game !', 400);
    }
}
