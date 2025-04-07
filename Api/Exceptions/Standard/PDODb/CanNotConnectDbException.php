<?php

namespace Api\Exceptions\Standard\PDODb;

use Api\Lib\Standard\HttpCodes\Code500;

class CanNotConnectDbException extends PDODbException
{

    use Code500;

    public function __construct(string $database, string $server)
    {
        $this->setHttpCode();
        $message = 'Não foi possivel conectar ao banco de dados "' . $database . '"'
            . ' no servidor "' . $server . '".';
        parent::__construct($message);
    }
}