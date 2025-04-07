<?php

namespace Api\Business\Queues\InitialVerifications\Check\Steps;

use Api\Business\Queues\InitialVerifications\Check\Check;
use Api\Exceptions\FactoryException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CheckInternalHeader extends Check
{

    public function handle(): bool
    {
        if (!$this->isOpenRoute()) {
            $this->checkHeader();
        }
        return parent::handle();
    }

    private function checkHeader(): void
    {
        if (!$this->isValid()) {
            $exc = 'Standard\Current\Company\InvalidAuthenticationException';
            throw FactoryException::create($exc);
        }
    }

    private function isValid(): bool
    {
        return $this->checkAuthorization();
    }

    public function checkAuthorization(): bool
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $_SERVER['HTTP_AUTHORIZATION'] = $headers['Authorization'];
        } else {
            $_SERVER['HTTP_AUTHORIZATION'] = null;
        }

        if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $exception = 'Standard\EndpointKey\InvalidConfigJWTException';
            throw FactoryException::create($exception, ['auth']);
        }

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        if (strpos($authHeader, 'Bearer ') !== 0) {
            $exception = 'Standard\EndpointKey\InvalidConfigJWTException';
            throw FactoryException::create($exception, ['auth']);
        }

        $jwtToken = substr($authHeader, 7);

        $secretKey = 'JWTSECRET123';
        $headers = array('HS256');
        try {
            JWT::decode($jwtToken, new Key($secretKey, 'HS256'));
            return true;
        } catch (\Exception) {
            $exception = 'Standard\EndpointKey\InvalidConfigJWTException';
            throw FactoryException::create($exception, ['auth']);
        }
    }
}