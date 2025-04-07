<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class TransferRouteTest extends TestCase
{
    public function testTransferInvalidReceiver()
    {
        $client = new Client(['base_uri' => 'http://localhost:9000']);

        try {
            $client->post('/transfer', [
                'form_params' => [
                    'payer' => 1,
                    'payee' => 9999,
                    'value' => 100.00
                ]
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $body = (string) $e->getResponse()->getBody();
            $data = json_decode($body, true);

            $this->assertArrayHasKey('error', $data);
            $this->assertStringContainsString('Usuário não encontrado', $data['error']['message']);
            $this->assertEquals('Api\\Exceptions\\Transfer\\UserNotFoundException', $data['error']['exceptionClass']);
        }
    }

    public function testTransferInvalidFields()
    {
        $client = new Client(['base_uri' => 'http://localhost:9000']);

        try {
            $client->post('/transfer', [
                'form_params' => [
                    'payer' => 'abc',     // inválido
                    'payee' => -1,        // inválido
                    'value' => 'dez'      // inválido
                ]
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $body = (string) $e->getResponse()->getBody();
            $data = json_decode($body, true);

            $this->assertArrayHasKey('error', $data);
            $this->assertStringContainsString('Erro de validação', $data['error']['message']);
            $this->assertEquals('Api\\Exceptions\\Transfer\\InvalidFieldsException', $data['error']['exceptionClass']);
            return;
        }

        $this->fail('A requisição deveria ter retornado erro de validação, mas foi bem-sucedida.');
    }
}
