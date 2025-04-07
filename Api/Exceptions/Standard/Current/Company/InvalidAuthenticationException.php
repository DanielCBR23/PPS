<?php

namespace Api\Exceptions\Standard\Current\Company;

use Api\Lib\Standard\HttpCodes\Code429;
use Exception;

class InvalidAuthenticationException extends Exception
{

    use Code429;

    public function __construct()
    {
        $this->setHttpCode();
        $message = 'Limite de requisições excedido.';
        parent::__construct($message);
    }
}