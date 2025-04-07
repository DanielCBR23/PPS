<?php

namespace Api\Exceptions\Standard\Environment;

use Api\Lib\Standard\HttpCodes\Code500;
use Api\Lib\Utils\Date;
use Exception;

class VariableNotFoundException extends Exception
{

    use Code500;

    private $key = '';

    public function __construct(string $key)
    {
        $this->setHttpCode();
        $this->key = $key;

        $message = 'Falha ao executar aplicação. Entre em contato.';
        parent::__construct($message);
    }

    protected function getMessageToTicket(string $message): string
    {
        return '[' . Date::now() . ']'
            . ' Variável "' . $this->key . '" não encontrada no getenv(). Verifique imediatamente.';
    }
}