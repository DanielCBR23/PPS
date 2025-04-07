<?php

namespace Api\Business\Queues\InitialVerifications\Check\Steps;

use Api\Business\Queues\InitialVerifications\Check\Check;
use Api\Exceptions\FactoryException;

class CheckCSRFToken extends Check
{

    public function handle(): bool
    {
        if (!$this->isOpenRoute()) {
            $this->checkCSFRToken();
        }
        return parent::handle();
    }

    private function checkCSFRToken(): void
    {
        $headers = getallheaders();

        if (!isset($headers['HTTP_X_CSRF_TOKEN'])) {
            throw new FactoryException('CSRF token is missing.');
        }

        $csrfToken = $headers['HTTP_X_CSRF_TOKEN'];
        if (!$this->isValidCSRFToken($csrfToken)) {
            throw new FactoryException('Invalid CSRF token.');
        }
    }

    private function isValidCSRFToken(string $token): bool
    {
        return $token === $_SESSION['csrf_token'];
    }
}