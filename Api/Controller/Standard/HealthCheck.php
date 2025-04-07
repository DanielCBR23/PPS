<?php

namespace Api\Controller\Standard;

use Api\Business\FactoryBusiness;

class HealthCheck extends Controller
{
    public function check(): void
    {
        FactoryBusiness::create('Data\HealthCheck\Check')->check();
    }
}