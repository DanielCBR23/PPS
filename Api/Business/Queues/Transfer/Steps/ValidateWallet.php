<?php

namespace Api\Business\Queues\Transfer\Steps;

use Api\Business\FactoryBusiness;
use Api\Business\Queues\Transfer\Execute;
use Api\Exceptions\FactoryException;
use PHPUnit\Runner\Filter\Factory;

class ValidateWallet extends Execute
{
    public function handle(): bool
    {
        $payer = $this->getUsers()['payer'];
        $amount = $this->getTransferData()['value'];
        $balance = FactoryBusiness::create('Entity\Wallets\Wallets')->getWalletByIdUser($payer->idUser);

        if (!$balance->hasData()) {
            throw FactoryException::create("Transfer\WalletNotFoundException");
        }

        if ($balance->getBalance() < $amount) {
            throw FactoryException::create("Transfer\InsufficientBalanceException");
        }
        
        return parent::handle();
    }
}
