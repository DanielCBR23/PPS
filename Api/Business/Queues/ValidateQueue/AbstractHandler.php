<?php

namespace Api\Business\Queues\ValidateQueue;

use Api\Exceptions\FactoryException;
use Api\Lib\Validator\ErrorDetails;
use Api\Lib\Standard\ObjectRefer;
use Api\Lib\Time\TimingHelper;

abstract class AbstractHandler implements Handler
{

    use ObjectRefer;

    private static $queue = [];
    private static $namespace;
    private static $time = null;

    abstract protected function getNamespaceStepsInnerApi(): array;

    abstract protected function getSteps(): array;

    abstract protected function getNamePathQueue(): string;

    protected function setQueueInfo(): void
    {
        self::$queue[$this->getNamePathQueue()] = $this->getSteps();
        self::$namespace[$this->getNamePathQueue()] = $this->getNamespaceStepsInnerApi();
    }

    private function getQueuePosition(string $step = '')
    {
        $queue = $this->getQueue();
        return array_search($step, $queue);
    }

    private function hasIndexInQueue(int $index)
    {
        $queue = $this->getQueue();
        return isset($queue[$index]);
    }

    private function getQueue($position = '')
    {
        if ($position !== '') {
            return self::$queue[$this->getNamePathQueue()][$position];
        }
        return self::$queue[$this->getNamePathQueue()];
    }

    public function init(string $stepToInit = ''): AbstractHandler
    {
        if (is_null(self::$time)) {
            self::$time = new TimingHelper();
        }
        $this->setQueueInfo();
        $keyToInit = 0;
        if (!empty($stepToInit)) {
            $keyToInit = $this->getQueuePosition($stepToInit);
        }
        $next = $this->getQueue($keyToInit);
        if (isset($next)) {
            $this->doNextInternal($next);
        }
        return $this;
    }

    protected function appendTime(string $step, bool $print = false): void
    {
        $time = self::$time->time();
        if ($print) {
            echo $time . '<br>';
        } else {
            ErrorDetails::getInstance()
                ->appendDetailWarning($step, self::$time->time());
        }
    }


    public function handle(): bool
    {
        $class = $this->getObjectName();
        $index = $this->getQueuePosition($class);
        $this->appendStepToLog($index);
        $indexNext = $index + 1;
        if ($this->hasIndexInQueue($indexNext)) {
            $next = $this->getQueue($indexNext);
            $this->doNextInternal($next);
        }
        return true;
    }

    private function doNextInternal(string $name): void
    {
        $object = $this->getObjectByName($this->getNamespaceQueue(), $name);
        if (!$object) {
            $className = implode('/', $this->getNamespaceQueue()) . '/' . $name;
            $exception = 'Standard\ValidateQueue\NotFoundNextObjectException';
            throw FactoryException::create($exception, [$className]);
        }
        $object->handle();
    }

    private function getNamespaceQueue(): array
    {
        return self::$namespace[$this->getNamePathQueue()];
    }

    private function appendStepToLog(int $index): void
    {
        $class = $this->getQueue($index);
        $object = $this->getObjectByName($this->getNamespaceQueue(), $class);
        $namespace = get_class($object);
    }
}