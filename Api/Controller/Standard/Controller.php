<?php

namespace Api\Controller\Standard;

use Api\Lib\Standard\FriendlyUrl;
use Api\Repository\Mapper\Standard\Mapper;
use Api\Repository\Mapper\Standard\ResponseMap;

class Controller
{

    public static $responseObject;

    protected function getRouteParam(int $param): string
    {
        $friendlyUrl = new FriendlyUrl();
        return $friendlyUrl->getParameter($param);
    }

    public function getResponseObject(): ResponseMap
    {
        if (self::$responseObject == null) {
            self::$responseObject = new ResponseMap();
        }
        return self::$responseObject;
    }

    protected function appendResponseType($type = true): void
    {
        $this->appendResponse('type', $type);
    }

    protected function appendResponse(string $name, $data): void
    {
        $this->getResponseObject()->addField($name, $data);
    }

    protected function appendArrayResponse(array $response): void
    {
        foreach ($response as $name => $data) {
            $this->appendResponse($name, $data);
        }
    }

    protected function appendResponseMapper(string $name, Mapper $mapper): void
    {
        $treatNameObject = get_class($mapper);
        $this->getResponseObject()->addField($name, $mapper, $treatNameObject, false, true);
    }

    protected function appendResponseArrayMappers(string $name, array $mappers): void
    {
        $class = (isset($mappers[0])) ? get_class($mappers[0]) : '';
        $treatNameArrayObjects = 'array' . $class;
        $this->getResponseObject()->addField($name, $mappers, $treatNameArrayObjects, false, true);
    }
}