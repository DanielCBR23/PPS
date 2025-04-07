<?php

namespace Api\Lib\Standard;


trait ObjectRefer
{

    use ClassName;

    public $lastObjectNameTryInstance = null;

    public function getObjectRefer(array $namespaceInfo, array $arguments = [])
    {
        array_unshift($namespaceInfo, 'Api');
        $namespaceInfo[] = $this->getObjectName();
        return $this->getObject($namespaceInfo, $arguments);
    }

    public function getObjectName(): string
    {
        return ReflectionClass::getShortName($this);
    }

    public function getObjectByName(array $namespaceInfo, string $className, array $arguments = [])
    {
        array_unshift($namespaceInfo, 'Api');
        $namespaceInfo[] = $className;
        return $this->getObject($namespaceInfo, $arguments);
    }

    private function getObject(array $data, array $arguments)
    {
        $class = '\\' . implode('\\', $data);
        if (class_exists($class)) {
            return new $class(...$arguments);
        }
        $this->lastObjectNameTryInstance = $class;
        return null;
    }
}