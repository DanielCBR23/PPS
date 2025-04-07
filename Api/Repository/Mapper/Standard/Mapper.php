<?php

namespace Api\Repository\Mapper\Standard;

use Api\Exceptions\FactoryException;
use Api\Lib\Standard\ClassName;
use Api\Repository\Mapper\Standard\Helpers\MapperData;
use Api\Repository\Mapper\Standard\Helpers\CompositionStatic;
use Api\Repository\Mapper\Standard\Helpers\RenderMapperToArray;

abstract class Mapper
{

    use ClassName,
        MapperData,
        CompositionStatic,
        RenderMapperToArray;

    private $data, $dataNotMapped;
    protected $composition;
    private static $treatFieldInDbPositionName = 'fieldInDb',
        $treatPositionName = 'treat',
        $map = [],
        $mapRegisters = [],
        $compositionConfig = [],
        $compositionRegisters = [];


    abstract protected function setMapConfig(): void;

    abstract public static function getNameRepository(): string;

    public function __construct($register = null, $nonExistingFields = [], array $compositions = [])
    {
        $this->setCompositionConfigEmpty();
        $this->appendCompositionsConstruct($compositions);
        if (!isset(self::$map[self::getObjNameToMap()])) {
            $this->setMapConfig();
        }
        $this->setData($register, $nonExistingFields);
    }

    public function __set($key, $value)
    {
        return $this->data[$key] = $value;
    }

    public function __get($key)
    {
        if (!isset($this->data[$key]) && self::$map[self::getObjNameToMap()][$key]['isTreatMethod']) {
            $fieldInDb = self::$map[self::getObjNameToMap()][$key][self::$treatFieldInDbPositionName];
            $method = $this->getMethodNameParameter($fieldInDb);
            $response = $this->$method();
            $this->data[$key] = $response;
            return $response;
        }
        return $this->getPureData($key);
    }

    private function getMethodNameParameter(string $fieldInDb): string
    {
        return str_replace('this_', '', $fieldInDb);
    }

    protected function appendMap($fieldInDb, $newName, $treat = '', bool $isTreatMethod = false, bool $isTreatMap = false)
    {
        self::$map[self::getObjNameToMap()][$newName] = array(
            self::$treatFieldInDbPositionName => $fieldInDb,
            self::$treatPositionName => $treat,
            'isTreatMethod' => $isTreatMethod,
            'isTreatMap' => $isTreatMap
        );
        self::$mapRegisters[self::getObjNameToMap()][$fieldInDb] = $newName;
    }

    public function getTreated($name)
    {
        $content = $this->getPureData($name);
        if ($content === '') {
            return;
        }
        if (self::$map[self::getObjNameToMap()][$name]['isTreatMap']) {
            return $content;
        }
        $treatmentType = self::$map[self::getObjNameToMap()][$name][self::$treatPositionName];
        if ($treatmentType == '') {
            return $content;
        }
        $class = '\Api\Lib\Treatments\Mapper\\' . ucfirst($treatmentType);
        if (!class_exists($class)) {
            $exception = 'Standard\Mapper\MapperTreatNotFoundException';
            throw FactoryException::create($exception, [$treatmentType]);
        }
        return $class::treat($content);
    }

    public function setData($register, $nonExistingFields = []): void
    {
        if (!empty($register)) {

            foreach (self::$map[self::getObjNameToMap()] as $newName => $fields) {
                if (isset($register[$fields['fieldInDb']])) {
                    $this->data[$newName] = $register[$fields['fieldInDb']];
                    if (strpos($fields['fieldInDb'], 'this_') !== false) {
                        $this->data[$fields['fieldInDb']] = $register[$fields['fieldInDb']]; //nao rodar o this de novo quando ja tem no construct
                    }
                } else if (isset($register[$newName])) {
                    $this->data[$newName] = $register[$newName];
                }
            }
            $compositionConfigObjectNames = $this->getCompositionsConfig();
            $namespace = $this->getNamespaceOthersMappers();
            foreach ($compositionConfigObjectNames as $objectName => $fieldMap) {
                if (str_contains($objectName, 'Api\Repository\Mapper')) {
                    $newObject = new $objectName($register);
                    $this->appendCompositionByClass($newObject, $objectName);
                    continue;
                }
                $completeName = '\\' . $namespace . '\\Repository\\Mapper\\' . $objectName . 'Map';
                $newObject = new $completeName($register);
                $fieldId = $newObject->getNameDBToComposition($fieldMap);

                if (isset($register[$fieldId]) && !empty($register[$fieldId])) {
                    $this->appendComposition($newObject, $objectName);
                }
            }
            $compositionRegisters = $this->getCompositionRegisters();
            foreach ($compositionRegisters as $objectName) {
                if (str_contains($objectName, 'Api\Repository\Mapper')) {
                    $newObject = new $objectName($register);
                    $this->appendCompositionByClass($newObject, $objectName);
                    continue;
                }
                $completeName = '\\' . $namespace . '\\Repository\\Mapper\\' . $objectName . 'Map';
                $newObject = new $completeName($register);
                if ($newObject->hasData()) {
                    $this->appendComposition($newObject, $objectName);
                }
            }
            foreach ($nonExistingFields as $field) {
                $this->dataNotMapped[$field] = $register[$field];
            }
        }
    }

    private function setCompositionConfigEmpty(): void
    {
        if (!isset(self::$compositionConfig[self::getObjNameToMap()])) {
            self::$compositionConfig[self::getObjNameToMap()] = [];
        }
    }

    private function appendCompositionsConstruct(array $compositions): void
    {
        if (!empty($compositions)) {
            foreach ($compositions as $name => $obj) {
                $this->appendComposition($obj, $name);
            }
        }
    }

    protected function addCompositionRegisters(string $objectName): void
    {
        self::$compositionRegisters[self::getObjNameToMap()][] = $objectName;
    }

    protected function getCompositionRegisters(): array
    {
        if (isset(self::$compositionRegisters[self::getObjNameToMap()])) {
            return self::$compositionRegisters[self::getObjNameToMap()];
        }
        return [];
    }

    protected function addCompositionConfig(string $objectName, string $mapNameFk): void
    {
        self::$compositionConfig[self::getObjNameToMap()][$objectName] = $mapNameFk;
    }

    public function getCompositionsConfig(): array
    {
        return self::$compositionConfig[self::getObjNameToMap()];
    }

    public function getNameDb(string $field): string
    {
        if (!isset(self::$map[self::getObjNameToMap()][$field])) {
            throw FactoryException::create('Standard\Mapper\FieldNotFoundInMapException', [$field, $this]);
        }
        return self::$map[self::getObjNameToMap()][$field]['fieldInDb'];
    }

    public function getNameDbToComposition(string $fieldName): string
    {
        $dataField = explode(',', $fieldName);
        return $this->getNameDb(current($dataField));
    }

    protected static function getObjNameToMap(): string
    {
        return self::getFirstNamespaceUsingClassName()
            . '|'
            . static::getNameRepository();
    }

    protected function getComposition($class)
    {
        if (isset($this->composition[$class])) {
            return $this->composition[$class];
        }
        $name = $class . ' em ' . get_class($this);
        $exception = 'Standard\Mapper\CompositionNotSetException';
        throw FactoryException::create($exception, [$name]);
    }

    public function appendComposition($obj, $class)
    {
        $namespace = $this->getNamespaceOthersMappers();
        $class = str_replace('\\' . $namespace . '\Repository\Mapper\\', '', $class);
        $class = str_replace('Map', '', $class);
        $this->composition[$class] = $obj;
    }

    public function appendCompositionByClass(object $obj, string $class): void
    {
        $this->composition[$class] = $obj;
    }

    public function isSettedInMapper(string $name)
    {
        return array_key_exists($name, self::$map[self::getObjNameToMap()]);
    }

    public function getKeysInMap(): array
    {
        return array_keys(self::$map[self::getObjNameToMap()]);
    }

    protected function getFieldInMap(string $field): array
    {
        return self::$map[self::getObjNameToMap()][$field];
    }

    protected function isFieldAnObject($field): bool
    {
        if (empty($field)) {
            return false;
        }
        return (bool) self::$map[self::getObjNameToMap()][$field]['isTreatMap'];
    }

    public function getNamespaceOthersMappers(): string
    {
        return 'Api';
    }
}