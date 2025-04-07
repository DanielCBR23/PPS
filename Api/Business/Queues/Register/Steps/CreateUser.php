<?php

namespace Api\Business\Queues\Register\Steps;

use Api\Business\FactoryBusiness;
use Api\Business\Queues\Register\Execute;
use Api\Exceptions\FactoryException;

class CreateUser extends Execute
{
    public function handle(): bool
    {
        $request = $this->getRequest();
        $insertedId = FactoryBusiness::create('Entity\Users\Users')->insert($request);

        if (empty($insertedId)) {
            throw FactoryException::create("Register\UserNotCreatedException");
        }
        FactoryBusiness::create('Entity\Wallets\Wallets')->insert($insertedId);
        return parent::handle();
    }
}