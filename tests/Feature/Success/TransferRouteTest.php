<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use tests\Database\Database;

class TransferRouteTest extends TestCase
{
    public function testTransferSuccess()
    {
        $client = new Client(['base_uri' => 'http://localhost:9000']);

        // Cria PAYER
        $payerId = $this->registerUser($client, 'Payer Test');
        // Cria PAYEE
        $payeeId = $this->registerUser($client, 'Payee Test');

        // Atualiza saldo do PAYER para permitir a transferência
        $this->setWalletBalance($payerId, 100);

        // Faz a transferência
        $transferResponse = $client->post('/transfer', [
            'form_params' => [
                'payer' => $payerId,
                'payee' => $payeeId,
                'value' => 10
            ],
            'http_errors' => false
        ]);

        $this->assertEquals(200, $transferResponse->getStatusCode());
        $body = json_decode($transferResponse->getBody()->getContents(), true);

        $this->assertArrayHasKey('type', $body);
        $this->assertTrue($body['type']);
        $this->assertEquals('Transferência realizada com sucesso.', $body['message']);
    }

    private function registerUser(Client $client, string $name): int
    {
        $response = $client->post('/register', [
            'form_params' => [
                'name' => $name,
                'document' => $this->generateValidCPF(),
                'email' => strtolower($name) . rand(1000, 9999) . '@test.com',
                'password' => '123456',
                'confirmPassword' => '123456',
                'typeUser' => 'COMMON'
            ],
            'http_errors' => false // <- importante para não lançar exceção
        ]);
    
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody()->getContents(), true);
    
        // Debug em caso de erro
        if ($statusCode !== 200) {
            throw new \Exception("Erro ao registrar usuário: HTTP $statusCode\n" . json_encode($body, JSON_PRETTY_PRINT));
        }
    
        return $body['user']['id'] ?? throw new \Exception("Resposta inesperada ao registrar usuário.");
    }

    private function setWalletBalance(int $userId, float $amount): void
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE wallets SET balance = ? WHERE user_id = ?");
        $stmt->execute([$amount, $userId]);
    }

    private function generateValidCPF(): string
    {
        $n = [];
        for ($i = 0; $i < 9; $i++) {
            $n[$i] = rand(0, 9);
        }

        $n[9] = ($n[0]*10 + $n[1]*9 + $n[2]*8 + $n[3]*7 + $n[4]*6 + $n[5]*5 + $n[6]*4 + $n[7]*3 + $n[8]*2) % 11;
        $n[9] = $n[9] < 2 ? 0 : 11 - $n[9];

        $n[10] = ($n[0]*11 + $n[1]*10 + $n[2]*9 + $n[3]*8 + $n[4]*7 + $n[5]*6 + $n[6]*5 + $n[7]*4 + $n[8]*3 + $n[9]*2) % 11;
        $n[10] = $n[10] < 2 ? 0 : 11 - $n[10];

        return implode('', $n);
    }
}
