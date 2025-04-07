<?php

namespace Api\Lib\Standard\HttpCodes;

trait Code429
{

    use CodeHttp;

    protected function getCodeHttp(): int
    {
        return 429;
    }
}