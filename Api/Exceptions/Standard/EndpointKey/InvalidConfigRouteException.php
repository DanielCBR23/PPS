<?php

namespace Api\Exceptions\Standard\EndpointKey;

use Api\Lib\Standard\HttpCodes\Code404;
use Exception;

class InvalidConfigRouteException extends Exception
{

    use Code404;

    public function __construct()
    {
        $this->setHttpCode();
        $message = 'Configuração de autenticação da rota é inválida.';
        parent::__construct($message);
    }
}