<?php

namespace Api\Exceptions\Transfer;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class FailedTransactionException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Erro no processamento da transferência.';
    parent::__construct($message);
  }
}
