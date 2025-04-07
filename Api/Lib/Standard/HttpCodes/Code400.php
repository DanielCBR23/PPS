<?php

namespace Api\Lib\Standard\HttpCodes;

trait Code400
{

    use CodeHttp;

    protected function getCodeHttp(): int
    {
        return 400;
    }
}