<?php

namespace Api\Exceptions\Standard\EndpointKey;

use Api\Lib\Standard\HttpCodes\Code404;
use Exception;

class InvalidEndpointException extends Exception
{

    use Code404;

    public function __construct()
    {
        $this->setHttpCode();
        $message = 'O endpoint é inválido, verifique os parâmetros da URL de sua requisição.';
        parent::__construct($message);
    }
}