<?php

namespace Api\Exceptions\Standard\Current\Company;

use Api\Lib\Standard\HttpCodes\Code401;
use Exception;

class LimitRequestException extends Exception
{

    use Code401;

    public function __construct()
    {
        $this->setHttpCode();
        $message = 'Autenticação Inválida.';
        parent::__construct($message);
    }
}