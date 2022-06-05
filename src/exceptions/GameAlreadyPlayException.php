<?php

namespace Auth\Exceptions;


class GameAlreadyPlayException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Game is over !', 400);
    }
}
