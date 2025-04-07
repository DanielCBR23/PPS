<?php

namespace Api\Exceptions\Register;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class InvalidFieldsException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Erro de validação: Os campos devem ser válidos.';
    parent::__construct($message);
  }
}
