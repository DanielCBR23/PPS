<?php

namespace Api\Lib\Standard\HttpCodes;

trait Code401
{

    use CodeHttp;

    protected function getCodeHttp(): int
    {
        return 401;
    }
}