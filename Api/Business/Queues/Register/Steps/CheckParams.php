<?php

namespace Api\Business\Queues\Register\Steps;

use Api\Business\Queues\Register\Execute;
use Api\Exceptions\FactoryException;
use Api\Lib\Current\Input;
use Respect\Validation\Validator as v;

class CheckParams extends Execute
{
    public function handle(): bool
    {
        try {
            $request = Input::getInstance()->getDataBodyRequest();
            $this->validateFields($request);
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            throw FactoryException::create("Register\InvalidFieldsException");
        }

        $this->setRequest($request);
        return parent::handle();
    }

    private function validateFields(array $data): void
    {
        $validator = v::key('email', v::email())
            ->key('name', v::stringType()->notEmpty())
            ->key('document', v::callback(function ($document) {
                return $this->isValidDocument($document);
            }))
            ->key('password', v::stringType()->length(3, null))
            ->key('confirmPassword', v::equals($data['password'] ?? ''))
            ->key('typeUser', v::in(['SHOPKEEPER', 'COMMON']));
    
        $validator->assert($data);
    }
    

    private function isValidDocument(string $document): bool
    {
        $cpfValidator = v::cpf();
        $cnpjValidator = v::cnpj();
        return $cpfValidator->validate($document) || $cnpjValidator->validate($document);
    }
}
