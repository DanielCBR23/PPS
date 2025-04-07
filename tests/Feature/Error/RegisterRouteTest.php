<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RegisterRouteTest extends TestCase
{
    public function testRegisterWithInvalidFields()
    {
        $client = new Client(['base_uri' => 'http://localhost:9000']);
    
        $response = $client->post('/register', [
            'form_params' => [
                // campos inválidos ou faltando
                'name' => '',
                'document' => '123', // CPF inválido
                'email' => 'email-invalido',
                'password' => '123',
                'confirmPassword' => '456', // não confere
                'typeUser' => '' // inválido
            ],
            'http_errors' => false // evita exceção do Guzzle com status >= 400
        ]);
    
        $this->assertEquals(400, $response->getStatusCode());
    
        $body = json_decode($response->getBody()->getContents(), true);
    
        $this->assertArrayHasKey('error', $body);
        $this->assertStringContainsString('Erro de validação', $body['error']['message']);
        $this->assertEquals('Api\\Exceptions\\Register\\InvalidFieldsException', $body['error']['exceptionClass']);
    }
}


