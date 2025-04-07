<?php

namespace Api\Exceptions\Transfer;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class UserNotFoundException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Usuário não encontrado.';
    $message .= ' Verifique se o usuário existe e se o ID está correto.';
    parent::__construct($message);
  }
}
