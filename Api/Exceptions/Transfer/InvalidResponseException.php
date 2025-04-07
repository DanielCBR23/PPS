<?php

namespace Api\Exceptions\Transfer;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class InvalidResponseException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Erro no processamento da transferência.';
    $message .= ' O retorno da API de transferência não é válido.';
    parent::__construct($message);
  }
}
