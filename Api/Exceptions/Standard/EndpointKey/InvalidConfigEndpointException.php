<?php

namespace Api\Exceptions\Standard\EndpointKey;

use Api\Lib\Standard\HttpCodes\Code404;
use Exception;

class InvalidConfigEndpointException extends Exception
{

    use Code404;

    public function __construct(string $config)
    {
        $this->setHttpCode();
        $message = 'Configuração "' . $config . '" inválida para o endpoint.';
        parent::__construct($message);
    }
}