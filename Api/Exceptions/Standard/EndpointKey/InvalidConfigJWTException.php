<?php

namespace Api\Exceptions\Standard\EndpointKey;

use Api\Lib\Standard\HttpCodes\Code404;
use Exception;

class InvalidConfigJWTException extends Exception
{

    use Code404;

    public function __construct()
    {
        $this->setHttpCode();
        $message = 'JWT informado é inválido.';
        parent::__construct($message);
    }
}