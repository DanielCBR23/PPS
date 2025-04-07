<?php

namespace Api\Business\Queues\Transfer\Steps;

use Api\Business\Queues\Transfer\Execute;
use Api\Exceptions\FactoryException;
use Psr\Log\LoggerInterface;

class ValidateAuthorization extends Execute
{
    public function handle(): bool
    {
        try {
            $this->fetchAuthorizationResponse();
            return parent::handle();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function fetchAuthorizationResponse(): string
    {
        $curl = curl_init('https://util.devi.tools/api/v2/authorize');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);
    
        $response = curl_exec($curl);
        curl_close($curl);
    
        $data = json_decode($response, true);
    
        if (!isset($data['data']['authorization'])) {
            throw FactoryException::create("Transfer\\InvalidResponseException");
        }
    
        if ($data['data']['authorization'] === false) {
            throw FactoryException::create("Transfer\\AuthorizationFetchException");
        }
        return $data['data']['authorization'];
    }
}
