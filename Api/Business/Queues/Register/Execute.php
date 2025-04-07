<?php

namespace Api\Business\Queues\Register;

use Api\Business\Queues\ValidateQueue\AbstractHandlerRequest;

class Execute extends AbstractHandlerRequest
{

    protected static $request = [];

    public function getNamespaceStepsInnerApi(): array
    {
        return [
            'Business',
            'Queues',
            'Register',
            'Steps'
        ];
    }

    public function getSteps(): array
    {
        return [
            'CheckParams',
            'CheckRequest',
            'CreateUser'
        ];
    }

    public function getNamePathQueue(): string
    {
        return 'Register';
    }

    public function getRepositoryName(): string
    {
        return '';
    }
    
    protected function setRequest(array $data): void
    {
        self::$request = $data;
    }
    protected function getRequest(): array
    {
        return self::$request;
    }
}