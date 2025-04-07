<?php

namespace Api\Lib\Notification;

use Api\Lib\Log\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class Notification
{

    private const DEFAULT_TIMEOUT = 5;

    private Log $logger;
    private Client $httpClient;
    private string $notificationUrl;

    public function __construct(?Client $client = null, ?string $notificationUrl = null)
    {
        $this->logger = new Log();
        $this->httpClient = $client ?? new Client();
        $this->notificationUrl = $notificationUrl ?? 'https://util.devi.tools/api/v1/notify';
    }

    public function sendNotification(string $email, string $message): bool
    {
        try {
            $response = $this->httpClient->post($this->notificationUrl, [
                'json' => [
                    'email' => $email,
                    'message' => $message
                ],
                'timeout' => self::DEFAULT_TIMEOUT
            ]);

            return $this->isSuccessfulResponse($response);
        } catch (RequestException $e) {
            $this->logger->error('Erro ao enviar e-mail', 'Notify', [
                'email' => $email,
                'message' => $message,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Notifica o destinatário que recebeu uma transferência.
     */
    public function notifyTransferReceived(string $recipientEmail, float $amount, string $senderName): bool
    {
        $message = sprintf(
            "Você recebeu uma transferência de R$ %.2f de %s no PPS",
            $amount,
            $senderName
        );

        return $this->sendNotification($recipientEmail, $message);
    }

    /**
     * Notifica o remetente que a transferência foi enviada.
     */
    public function notifyTransferSent(string $senderEmail, float $amount, string $recipientName): bool
    {
        $message = sprintf(
            "Você enviou uma transferência de R$ %.2f para %s no PPS",
            $amount,
            $recipientName
        );

        return $this->sendNotification($senderEmail, $message);
    }

    private function isSuccessfulResponse(ResponseInterface $response): bool
    {
        $statusCode = $response->getStatusCode();
        return $statusCode >= 200 && $statusCode < 300;
    }
}
