<?php

namespace Api\Business\Queues\Register\Steps;

use Api\Business\FactoryBusiness;
use Api\Business\Queues\Register\Execute;
use Api\Exceptions\FactoryException;

class CheckRequest extends Execute
{
    public function handle(): bool
    {
        $this->checkExistDocument();
        $this->checkExistEmail();
        return parent::handle();
    }

    private function checkExistDocument(): void
    {
        $document = $this->getRequest()['document'];
        $hasDocument = FactoryBusiness::create('Entity\Users\Users')->getUserByDocument($document);
        if ($hasDocument) {
            throw FactoryException::create("Register\DocumentAlreadyExistsException");
        }
    }

    private function checkExistEmail(): void
    {
        $email = $this->getRequest()['email'];
        $hasEmail = FactoryBusiness::create('Entity\Users\Users')->getUserByEmail($email);
        if ($hasEmail) {
            throw FactoryException::create("Register\EmailAlreadyExistsException");
        }
    }
}
