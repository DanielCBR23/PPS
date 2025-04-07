<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RegisterRouteTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $client = new Client(['base_uri' => 'http://localhost:9000']);

        $randomId = rand(1000, 9999);
        $email = "user{$randomId}@example.com";
        $cpf = $this->generateValidCPF();

        $response = $client->post('/register', [
            'form_params' => [
                'name' => 'Teste Name',
                'document' => $cpf,
                'email' => $email,
                'password' => '123456',
                'confirmPassword' => '123456',
                'typeUser' => 'COMMON'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('type', $body);
        $this->assertTrue($body['type']);
    }

    private function generateValidCPF(): string
    {
        $n = [];
        for ($i = 0; $i < 9; $i++) {
            $n[$i] = rand(0, 9);
        }

        // Primeiro dígito
        $n[9] = ($n[0]*10 + $n[1]*9 + $n[2]*8 + $n[3]*7 + $n[4]*6 + $n[5]*5 + $n[6]*4 + $n[7]*3 + $n[8]*2) % 11;
        $n[9] = $n[9] < 2 ? 0 : 11 - $n[9];

        // Segundo dígito
        $n[10] = ($n[0]*11 + $n[1]*10 + $n[2]*9 + $n[3]*8 + $n[4]*7 + $n[5]*6 + $n[6]*5 + $n[7]*4 + $n[8]*3 + $n[9]*2) % 11;
        $n[10] = $n[10] < 2 ? 0 : 11 - $n[10];

        return implode('', $n);
    }
}


