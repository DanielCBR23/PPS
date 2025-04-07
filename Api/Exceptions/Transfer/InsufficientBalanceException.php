<?php

namespace Api\Exceptions\Transfer;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class InsufficientBalanceException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Saldo insuficiente para realizar a transferência.';
    parent::__construct($message);
  }
}
