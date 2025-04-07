<?php

namespace Api\Business\Data\HealthCheck\Checks;

use Api\Lib\Standard\Environment\Variables;

trait Environment
{

    protected function checkEnvironment(): void
    {
        $environment = Variables::getInstance()->get('ENVIRONMENT');
        $this->appendResponse('environment', $environment);
    }
}