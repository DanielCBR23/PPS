<?php

namespace Api\Lib\Standard\HttpCodes;

trait Code404
{

    use CodeHttp;

    protected function getCodeHttp(): int
    {
        return 404;
    }
}