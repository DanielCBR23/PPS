<?php

namespace Api\Business\Queues\Transfer\Steps;

use Api\Business\FactoryBusiness;
use Api\Business\Queues\Transfer\Execute;
use Api\Exceptions\FactoryException;
use Api\Lib\Log\Log;

class ExecuteTransfer extends Execute
{
    
    private Log $log;

    public function __construct()
    {
        $this->log = new Log();
    }

    public function handle(): bool
    {
        try {
            $this->log->info("Iniciando execução de transferência",'Transfer', $this->getTransferData());

            $walletRepo = FactoryBusiness::create('Entity\Wallets\Wallets');
            $walletRepo->beginTransaction();

            $transferId = $this->createTransferRecord();
            $this->setInsertedId($transferId);
            $this->log->info("Registro de transferência criado",'Transfer', ['transferId' => $transferId]);

            $payer = $this->getUserPayer();
            $payee = $this->getUserPayee();

            $walletRepo->updateBalance($payer);
            $this->log->info("Saldo do pagador atualizado", 'Transfer',['userId' => $payer->userIdWallet, 'novoSaldo' => $payer->balanceWallet]);

            $walletRepo->updateBalance($payee);
            $this->log->info("Saldo do recebedor atualizado", 'Transfer',['userId' => $payee->userIdWallet, 'novoSaldo' => $payee->balanceWallet]);

            $walletRepo->commit();
            $this->updateTransferStatus('completed');
            $this->log->info("Transferência concluída com sucesso", 'Transfer', ['transferId' => $transferId]);

        } catch (\Throwable $e) {
            $walletRepo->rollBack();
            $this->updateTransferStatus('failed');

            $this->log->error("Erro ao executar transferência",'Transfer', [
                'erro' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw FactoryException::create("Transfer\FailedTransactionException");
        }

        return parent::handle();
    }

    private function getUserPayer()
    {
        $users = $this->getUsers();
        $amount = number_format($this->getTransferData()['value'], 2, '.', '');
        $userPayer = FactoryBusiness::create('Entity\Wallets\Wallets')->getWalletByIdUser($users['payer']->idUser);
        $userPayer->balanceWallet -= $amount;
        return $userPayer;
    }

    private function getUserPayee()
    {
        $users = $this->getUsers();
        $amount = number_format($this->getTransferData()['value'], 2, '.', '');
        $userPayee = FactoryBusiness::create('Entity\Wallets\Wallets')->getWalletByIdUser($users['payee']->idUser);
        $userPayee->balanceWallet += $amount;
        return $userPayee;
    }

    private function createTransferRecord(): int
    {
        $data = $this->getTransferData();
        $users = $this->getUsers();

        $transferRepository = FactoryBusiness::create('Entity\Transfers\Transfers');

        return $transferRepository->create([
            'payerId_transfer' => $users['payer']->idUser,
            'payeeId_transfer' => $users['payee']->idUser,
            'amount_transfer' => number_format($data['value'], 2, '.', ''),
            'status_transfer' => 'pending',
            'created_at_transfer' => date('Y-m-d H:i:s'),
            'updated_at_transfer' => date('Y-m-d H:i:s'),
        ]);
    }

    private function updateTransferStatus(string $status): void
    {
        $transferId = $this->getInsertedId();
        if (!$transferId) return;

        $transferRepository = FactoryBusiness::create('Entity\Transfers\Transfers');

        $transferRepository->update($transferId, [
            'status_transfer' => $status,
            'updated_at_transfer' => date('Y-m-d H:i:s'),
        ]);

        $this->log->info("Status da transferência atualizado", 'Transfer', [
            'transferId' => $transferId,
            'novoStatus' => $status
        ]);
    }
}
