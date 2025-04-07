<?php

namespace Api\Lib\Standard\HttpCodes;

trait Code200
{

    use CodeHttp;

    protected function getCodeHttp(): int
    {
        return 200;
    }
}