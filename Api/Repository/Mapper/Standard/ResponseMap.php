<?php

namespace Api\Repository\Mapper\Standard;

class ResponseMap extends Mapper
{

    use MapperReturnWebservice;

    public function setMapConfig(): void
    {
        
    }

    protected function setFieldsToReturnInWebservice(): void
    {
        
    }

    public static function getNameRepository(): string
    {
        return '';
    }

    public function addField(string $fieldName, $content, string $treat = '', bool $isMethodTreat = false, bool $isMapTreat = false): void
    {
        $this->setDataField($fieldName, $content, $treat, $isMethodTreat, $isMapTreat);
        $this->addFieldToReturnWebservice($fieldName);
    }
}