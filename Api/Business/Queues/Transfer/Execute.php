<?php

namespace Api\Business\Queues\Transfer;

use Api\Business\Queues\ValidateQueue\AbstractHandlerRequest;
use Api\Repository\Mapper\User\UserMap;

class Execute extends AbstractHandlerRequest
{

    protected static $request = [], $insertedId, $token;

    public function getNamespaceStepsInnerApi(): array
    {
        return [
            'Business',
            'Queues',
            'Transfer',
            'Steps'
        ];
    }

    public function getSteps(): array
    {
        return [
            'CheckParams',
            'ValidateUsers',
            'ValidateWallet',
            'ValidateAuthorization',
            'ExecuteTransfer',
            'NotifyReceiver',
        ];
    }

    public function getNamePathQueue(): string
    {
        return 'Transfer';
    }

    public function getRepositoryName(): string
    {
        return '';
    }

    protected function setTransferData(array $data): void
    {
        self::$request = $data;
    }
    protected function getTransferData(): array
    {
        return self::$request;
    }

    protected function setUsers(UserMap $payer, UserMap $payee): void
    {
        self::$request['payer'] = $payer;
        self::$request['payee'] = $payee;
    }

    protected function getUsers(): array
    {
        return [
            'payer' => self::$request['payer'],
            'payee' => self::$request['payee']
        ];
    }

    protected function setInsertedId(int $id): void
    {
        self::$insertedId = $id;
    }

    protected function getInsertedId(): ?int
    {
        return self::$insertedId ?? null;
    }
}
