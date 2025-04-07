<?php

namespace Api\Repository\Mapper\TransferMap;

use Api\Repository\Mapper\Standard\Mapper;

class TransferMap extends Mapper
{

    protected function setMapConfig(): void
    {
        $this->appendMap('id_transfer', 'idTransfer', '', false);
        $this->appendMap('payerId_transfer', 'payerIdTransfer', '', false);
        $this->appendMap('payeeId_transfer', 'payeeIdTransfer', '', false);
        $this->appendMap('amount_transfer', 'amountTransfer', '', false);
        $this->appendMap('status_transfer', 'statusTransfer', '', false);
        $this->appendMap('created_at_transfer', 'createdAtTransfer', '', false);
        $this->appendMap('updated_at_transfer', 'updatedAtTransfer', '', false);
    }

    public static function getNameRepository(): string
    {
        return 'Transfers/Transfers';
    }
}
