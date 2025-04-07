<?php

namespace Api\Lib\Standard\HttpCodes;

trait CodeHttp
{

    abstract protected function getCodeHttp(): int;

    public function setHttpCode(): void
    {
        http_response_code($this->getCodeHttp());
    }
}