<?php

namespace Api\Exceptions\Transfer;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class InvalidFieldsException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Erro de validação: payer, payee e value devem ser números inteiros positivos.';
    parent::__construct($message);
  }
}
