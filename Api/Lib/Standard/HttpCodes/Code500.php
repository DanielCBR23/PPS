<?php

namespace Api\Lib\Standard\HttpCodes;

trait Code500
{

    use CodeHttp;

    protected function getCodeHttp(): int
    {
        return 500;
    }
}