<?php

namespace Api\Business\Queues\ValidateQueue;

use Api\Lib\Treatments\ArrayTreat;
use Api\Repository\Mapper\Standard\Mapper;
use Api\Business\FactoryBusiness;
use Api\Lib\Standard\ObjectRefer;
use Api\Lib\Current\Input;
use ReflectionClass;

abstract class AbstractHandlerRequest extends AbstractHandler
{

    use ObjectRefer;

    private $isDataSet = false;
    private static $data = null;
    private static $mapper = null;

    abstract protected function getRepositoryName(): string;

    protected function resetOnInit(): bool
    {
        return false;
    }

    public function init(string $stepToInit = '', ?bool $isToReset = null): AbstractHandler
    {
        $this->resetQueueStaticData($isToReset);
        $this->setDefaultData();
        $response = parent::init($stepToInit);
        return $response;
    }

    private function resetQueueStaticData(?bool $isToReset = null): void
    {
        $isToReset = $isToReset ?? $this->resetOnInit();

        if (!$isToReset) {
            return;
        }

        $currentQueue = get_called_class();

        $class = new ReflectionClass($currentQueue);
        $staticProps = array_keys($class->getStaticProperties());

        array_walk($staticProps, function (string $name) use ($class) {
            $property = $class->getProperty($name);

            if (!$property->hasDefaultValue()) return;

            $defaultValue = $property->getDefaultValue();
            $property->setValue($defaultValue);
        });
    }

    protected function setDefaultData(): void
    {
        if (!$this->isDataSet) {
            $inputData = Input::getInstance()->getDataBodyRequest();
            $this->setData($inputData);
        }
    }

    public function setData(array $data): AbstractHandlerRequest
    {
        $this->isDataSet = true;
        self::$data[$this->getNamePathQueue()] = $data;
        return $this;
    }

    public function getData(): array
    {
        if (is_array(self::$data[$this->getNamePathQueue()])) {
            return self::$data[$this->getNamePathQueue()];
        }
        return [];
    }

    public function setMapper($mapper): AbstractHandlerRequest
    {
        self::$mapper[$this->getNamePathQueue()] = $mapper;
        return $this;
    }

    protected function getMapper(): ?Mapper
    {
        if (!isset(self::$mapper[$this->getNamePathQueue()])) {
            return null;
        }
        return self::$mapper[$this->getNamePathQueue()];
    }

    public function getMapperToResponse(): ?Mapper
    {
        return $this->getRepository()
            ->getByGalaxPayId($this->getMapper()->galaxPayId);
    }

    protected function getRepository()
    {
        $className = 'Data\\' . $this->getRepositoryName();
        return FactoryBusiness::create($className);
    }

    public function getDataField(string $fieldName, $notFoundReturn = ''): mixed
    {
        $data = $this->getData();

        return ArrayTreat::get(
            $data,
            $fieldName,
            $notFoundReturn
        );
    }
}