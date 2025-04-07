<?php

namespace Api\Business\Queues\InitialVerifications\Check;

use Api\Business\Queues\ValidateQueue\AbstractHandlerRequest;
use Api\Lib\Current\Routes;

class Check extends AbstractHandlerRequest
{
    protected function getNamespaceStepsInnerApi(): array
    {
        return [
            'Business',
            'Queues',
            'InitialVerifications',
            'Check',
            'Steps'
        ];
    }

    protected function getSteps(): array
    {
        return [
            'CheckRouteExists',
            // 'CheckBlacklistIpRequests',
            'CheckInternalHeader',
            'CheckLimitIpRequests',
            'CheckCSRFToken',
            // 'AuthenticateInCompany',
            // 'AuthenticateUser',
        ];
    }

    protected function getNamePathQueue(): string
    {
        return 'InitialVerifications';
    }
    
    protected function getRepositoryName(): string
    {
        return 'Company\Companies';
    }

    protected function isOpenRoute(): bool
    {
        $router = Routes::getInstance()->router();
        return $router->isOpenRoute();
    }

}