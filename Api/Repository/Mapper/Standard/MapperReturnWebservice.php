<?php

namespace Api\Repository\Mapper\Standard;

trait MapperReturnWebservice
{

    private static $fieldsToReturnWebservice = [];
    private static $fieldsToReturnWebserviceDebug = [];

    abstract protected function setFieldsToReturnInWebservice(): void;

    private function setFieldsToReturnInWebserviceDebug()
    {
        $keys = $this->getKeysInMap();
        foreach ($keys as $mapName) {
            $this->addFieldToReturnWebserviceDebug($mapName);
        }
    }

    public function getFieldsToReturnWebservice(): array
    {
        if (!$this->isSettedFieldsToReturnWebservice()) {
            $this->setFieldsToReturnInWebservice();
        }
        return self::$fieldsToReturnWebservice[self::getObjNameToMap()];
    }

    private function isSettedFieldsToReturnWebservice(): bool
    {
        return !empty(self::$fieldsToReturnWebservice[self::getObjNameToMap()]);
    }

    private function isSettedFieldsToReturnWebserviceDebug(): bool
    {
        return !empty(self::$fieldsToReturnWebserviceDebug[self::getObjNameToMap()]);
    }

    public function getFieldsToReturnWebserviceDebug(): array
    {
        if (!$this->isSettedFieldsToReturnWebserviceDebug()) {
            $this->setFieldsToReturnInWebserviceDebug();
        }
        return self::$fieldsToReturnWebserviceDebug[self::getObjNameToMap()];
    }

    protected function addFieldToReturnWebservice(string $field): void
    {
        $fields = null;
        if (isset(self::$fieldsToReturnWebservice[self::getObjNameToMap()])) {
            $fields = self::$fieldsToReturnWebservice[self::getObjNameToMap()];
        }
        if (is_null($fields) || !in_array($field, $fields)) {
            self::$fieldsToReturnWebservice[self::getObjNameToMap()][] = $field;
        }
    }

    protected function addFieldToReturnWebserviceDebug(string $field): void
    {
        self::$fieldsToReturnWebserviceDebug[self::getObjNameToMap()][] = $field;
    }

    protected function removeFieldToReturnWebservice(string $field): void
    {
        $key = array_search($field, self::$fieldsToReturnWebservice[self::getObjNameToMap()]);
        if (is_numeric($key)) {
            unset(self::$fieldsToReturnWebservice[self::getObjNameToMap()][$key]);
        }
    }
}