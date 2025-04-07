<?php

namespace Api\Business\Queues\Transfer\Steps;

use Api\Business\Queues\Transfer\Execute;
use Api\Lib\Log\Log;
use Api\Lib\Notification\Notification;

class NotifyReceiver extends Execute
{

    private Log $log;
    private Notification $notification;

    public function __construct()
    {
        $this->log = new Log();
        $this->notification = new Notification();
    }

    public function handle(): bool
    {
        $this->notifySender();
        $this->notifyReceiver();

        return parent::handle();
    }

    private function notifySender(): void
    {
        $payer = $this->getTransferData()['payer'];
        $payee = $this->getTransferData()['payee'];

        try {
            $this->notification->notifyTransferSent(
                $payer->emailUser,
                $this->getTransferData()['value'],
                $payee->nameUser
            );
            $this->log->info("E-mail enviado com sucesso!", 'NotifySender', [
                $payer->emailUser,
            ]);
        } catch (\Exception $e) {
            $this->log->error("Erro ao notificar o remetente da transferência", 'NotifySender', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function notifyReceiver(): void
    {
        $payer = $this->getTransferData()['payer'];
        $payee = $this->getTransferData()['payee'];

        try {
            $this->notification->notifyTransferReceived(
                $payee->emailUser,
                $this->getTransferData()['value'],
                $payer->nameUser
            );
            $this->log->info("E-mail enviado com sucesso!", 'NotifyReceiver', [
                $payee->emailUser,
            ]);
        } catch (\Exception $e) {
            $this->log->error("Erro ao notificar o destinatário da transferência", 'NotifyReceiver', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
