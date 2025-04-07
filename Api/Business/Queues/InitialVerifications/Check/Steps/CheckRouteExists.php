<?php

namespace Api\Business\Queues\InitialVerifications\Check\Steps;

use Api\Business\Queues\InitialVerifications\Check\Check;
use Api\Exceptions\FactoryException;
use Api\Lib\Current\Routes;

class CheckRouteExists extends Check
{

    public function handle(): bool
    {
        $this->checkExistentRoute();
        return parent::handle();
    }

    private function checkExistentRoute(): void
    {
        $route = Routes::getInstance()->router();
        if (empty($route->getRoute())) {
            $exception = 'Standard\EndpointKey\InvalidEndpointException';
            throw FactoryException::create($exception);
        }
    }
}