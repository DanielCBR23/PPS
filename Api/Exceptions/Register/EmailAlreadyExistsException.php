<?php

namespace Api\Exceptions\Register;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class EmailAlreadyExistsException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Erro de validação: O e-mail já existe.';
    parent::__construct($message);
  }
}
