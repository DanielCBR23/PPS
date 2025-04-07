<?php

namespace Api\Exceptions\Standard\ValidateQueue;

use Api\Lib\Standard\HttpCodes\Code500;
use Exception;

class NotFoundNextObjectException extends Exception
{

    use Code500;

    public function __construct(string $className)
    {
        $this->setHttpCode();
        $message = 'Não foi possivel encontrar o próximo o  bjeto da fila "' . $className . '"';
        parent::__construct($message);
    }
}