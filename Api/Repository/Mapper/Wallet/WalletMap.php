<?php

namespace Api\Repository\Mapper\Wallet;

use Api\Repository\Mapper\Standard\Mapper;

class WalletMap extends Mapper
{

    protected function setMapConfig(): void
    {
        $this->appendMap('id_wallet', 'idWallet', '', false);
        $this->appendMap('userId_wallet', 'userIdWallet', '', false);
        $this->appendMap('balance_wallet', 'balanceWallet', '', false);
        $this->appendMap('created_at_wallet', 'createdAtWallet', '', false);
        $this->appendMap('updated_at_wallet', 'updatedAtWallet', '', false);
    }

    public static function getNameRepository(): string
    {
        return 'Wallets/Wallets';
    }

    public function getBalance(): string
    {
        return $this->balanceWallet;
    }
}
