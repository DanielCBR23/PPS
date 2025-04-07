<?php

namespace Api\Exceptions\Register;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class DocumentAlreadyExistsException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Erro de validação: O documento já existe.';
    parent::__construct($message);
  }
}
