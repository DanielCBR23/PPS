<?php

namespace Api\Lib\Current;

use Api\Exceptions\FactoryException;
use Api\Lib\AutomatedTest\AutomatedTestConfig;

use function Fp\Util\jsonEncode;

class Input
{

    private static $instance,
        $endpoint,
        $parameteresGet,
        $bodyRequest,
        $httpMethod;

    public static function getInstance(): Input
    {
        if (self::$instance == null) {
            self::$instance = new Input();
        }
        return self::$instance;
    }

    public function init(bool $isToCheckRoute = true): void
    {
        // if (AutomatedTestConfig::isAutomatedTest()) {
        //     return;
        // }

        if ($isToCheckRoute) {
            $this->checkRoute();
            $this->setEndpoint($_GET['route']);
        }
        $this->setParametersGet();
        $this->setBodyRequest();
        $this->checkIsValidJsonRequest();
        $this->setHttpMethod();
    }

    private function checkRoute(): void
    {
        die('a');
        if (!is_string($_GET['route'])) {
            throw FactoryException::create('Standard\EndpointKey\InvalidEndpointException');
        }
    }

    public function setEndpoint(string $endpoint): void
    {
        self::$endpoint = $endpoint;
    }

    private function setParametersGet(): void
    {
        $parameteresGet = $_GET;
        unset($parameteresGet['route']);
        self::$parameteresGet = $parameteresGet;
    }

    private function setBodyRequest(): void
    {
        self::$bodyRequest = file_get_contents('php://input');
    }

    private function checkIsValidJsonRequest(): void
    {
        if (empty(self::$bodyRequest)) {
            return;
        }
        if ($_SERVER['CONTENT_TYPE'] != 'application/json') {
            return;
        }
        json_decode(self::$bodyRequest);
        $errors = array(
            JSON_ERROR_DEPTH => 'A profundidade máxima da pilha foi excedida no JSON da requisição.',
            JSON_ERROR_STATE_MISMATCH => 'JSON da requisição inválido ou mal formado.',
            JSON_ERROR_CTRL_CHAR => 'Erro de caractere de controle, possivelmente codificado incorretamente no JSON da requisição.',
            JSON_ERROR_SYNTAX => 'Erro de sintaxe no JSON da requisição.',
            JSON_ERROR_UTF8 => 'Caracteres UTF-8 malformado no JSON da requisição, possivelmente codificado incorretamente.'
        );
        $lastError = json_last_error();
        if (isset($errors[$lastError])) {
            throw FactoryException::create('Standard\Current\Input\InvalidJsonException', array($errors[$lastError]));
        }
    }

    public function getJsonBodyRequest(): string
    {
        return json_encode(json_decode(self::$bodyRequest));
    }

    public function getDataBodyRequest()
    {
        return $_POST ? $_POST : [];
    }

    public function getUrlCompleteRequest(): string
    {
        return self::$endpoint . '?' . http_build_query(self::$parameteresGet);
    }

    public function getEndpoint(): string
    {
        // if (AutomatedTestConfig::isAutomatedTest()) {
        //     return $_SERVER['REQUEST_URI'];
        // }

        return self::$endpoint;
    }

    private function setHttpMethod(): void
    {
        self::$httpMethod = strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function getHttpMethod(): string
    {
        return self::$httpMethod;
    }

    public function isGetMethod(): bool
    {
        return ($this->getHttpMethod() == 'GET');
    }

    public function isPostMethod(): bool
    {
        return ($this->getHttpMethod() == 'POST');
    }
}