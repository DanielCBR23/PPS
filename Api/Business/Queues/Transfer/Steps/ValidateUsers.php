<?php

namespace Api\Business\Queues\Transfer\Steps;

use Api\Business\FactoryBusiness;
use Api\Business\Queues\Transfer\Execute;
use Api\Exceptions\FactoryException;
use Api\Models\User;

class ValidateUsers extends Execute
{
    public function handle(): bool
    {
        $data = $this->getTransferData();

        $payer = FactoryBusiness::create('Entity\Users\Users')->getUserById($data['payer']);
        $payee = FactoryBusiness::create('Entity\Users\Users')->getUserById($data['payee']);
        
        if (!$payer->hasData() || !$payee->hasData()) {
            throw FactoryException::create("Transfer\UserNotFoundException");
        }

        if ($payer->hasShoopkeeper()) {
            throw FactoryException::create("Transfer\ShopkeeperCannotTransferException");
        }

        $this->setUsers($payer, $payee);
        return parent::handle();
    }
}
