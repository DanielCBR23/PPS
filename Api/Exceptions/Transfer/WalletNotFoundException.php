<?php

namespace Api\Exceptions\Transfer;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class WalletNotFoundException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Carteira não encontrada.';
    $message .= ' Verifique se o ID da carteira está correto e se a carteira existe.';
    parent::__construct($message);
  }
}
