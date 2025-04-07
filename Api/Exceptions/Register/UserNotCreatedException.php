<?php

namespace Api\Exceptions\Register;

use Api\Lib\Standard\HttpCodes\Code400;
use Exception;

class UserNotCreatedException extends Exception
{

  use Code400;

  public function __construct()
  {
    $this->setHttpCode();
    $message = 'Usuario não foi criado: Ocorreu um erro ao criar o usuário.';
    parent::__construct($message);
  }
}
