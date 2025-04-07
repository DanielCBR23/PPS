<?php

namespace Api\Lib\Standard;


trait ClassName
{

    public function getNameOfClass()
    {
        return static::class;
    }

    public function getShortNameUsingClassName(): string
    {
        $name = $this->getNameOfClass();
        $data = explode('\\', $name);
        return end($data);
    }

    public function getNamespaceUsingClassName(): string
    {
        $name = $this->getNameOfClass();
        $data = explode('\\', $name);
        array_pop($data);
        return implode('\\', $data);
    }

    protected static function getFirstNamespaceUsingClassName(): string
    {
        $name = static::class;
        $data = explode('\\', $name);
        return current($data);
    }
}