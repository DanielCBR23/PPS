<?php

namespace Api\Business\Data\HealthCheck;

use Api\Business\Data\HealthCheck\Checks\Environment;
use Api\Business\Data\HealthCheck\Checks\Database;

class Check
{

    use Database,
        Environment;

    private $response = [];
    private $data = [];

    public function check(): void
    {
        $this->checkEnvironment();
        $this->checkDatabase();
        $this->setHttpCode();
        $this->print();
    }

    protected function appendCheck(string $key, bool $isWorking): void
    {
        $this->data[$key] = $isWorking;
    }

    protected function appendResponse(string $key, mixed $value): void
    {
        $this->response[$key] = $value;
    }

    private function setHttpCode(): void
    {
        $data = $this->data;
        if (count(array_unique($data)) === 1 && current($data)) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    }

    private function print(): void
    {
        $dataToResponse = array_merge($this->response, $this->data);
        $response = json_encode($dataToResponse);
        die($response);
    }
}