<?php

namespace Api\Repository\Mapper\Standard\Helpers;

use Api\Exceptions\FactoryException;

trait MapperData
{

    private function getPureData($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        return '';
    }
    
    public function getDataOfNonExistingField(string $name)
    {
        return $this->dataNotMapped[$name];
    }

    public function setDataOfNonExistingField(string $name, $value)
    {
        $this->dataNotMapped[$name] = $value;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function hasData(): bool
    {
        return !empty($this->data);
    }

    public function hasGalaxPayId(): bool
    {
        return $this->galaxPayId;
    }

    public function getGalaxPayId(): int
    {
        return $this->galaxPayId;
    }

    protected function setDataField($field, $content, $treat = '', bool $isMethodTreat = false, bool $isMapTreat = false): void
    {
        $this->data[$field] = $content;
        $this->appendMap($field, $field, $treat, $isMethodTreat, $isMapTreat);
    }

    public function getArrayByData(): array
    {
        $fields = array_keys($this->data);
        return $this->getArrayToSql($fields);
    }
    
    public function getArrayToSql(array $fields): array
    {
        $return = array();
        foreach ($fields as $field) {
            $fieldInfo = $this->getFieldInMap($field);
            if ($fieldInfo['treat'] == 'VirtualField') {
                continue;
            }
            $dbField = $fieldInfo['fieldInDb'];
            if (empty($dbField)) {
                $exc = 'Standard\Mapper\FieldNotFoundInMapException';
                $args = [$field, $this];
                throw FactoryException::create($exc, $args);
            }
            $return[$dbField] = $this->data[$field];
        }
        return $return;
    }

}