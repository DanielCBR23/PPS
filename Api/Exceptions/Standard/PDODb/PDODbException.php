<?php

namespace Api\Exceptions\Standard\PDODb;

use Api\Lib\Standard\HttpCodes\Code500;
use Api\Exceptions\Standard\Tickets\SaveTicketException;
use Exception;

class PDODbException extends Exception
{

    use Code500;

    private $msgError = '';

    public function __construct(string $error = '')
    {
        $this->setHttpCode();
        $this->msgError = $error;
        $message = ((IS_OFFLINE) ? $error : 'Instabilidade no servidor, tente mais tarde.');

        parent::__construct($message);
    }

    protected function getMessageToTicket(string $message): string
    {
        return $this->msgError;
    }
}