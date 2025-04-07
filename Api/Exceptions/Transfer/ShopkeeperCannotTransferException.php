<?php

namespace Api\Exceptions\Transfer;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class ShopkeeperCannotTransferException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'A transferência não pode ser realizada.';
    $message .= ' O usuário é um lojista e não pode transferir valores.';
    parent::__construct($message);
  }
}