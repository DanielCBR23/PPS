<?php

namespace Api\Business\Queues\InitialVerifications\Check\Steps;

use Api\Business\Queues\InitialVerifications\Check\Check;


class CheckLimitIpRequests extends Check
{

    private $rateLimit = 100; 
    private $timeWindow = 3600; 
    private $ipRequests;

    public function __construct()
    {
        $this->ipRequests = $this->loadIpRequests();
    }

    public function __destruct()
    {
        $this->saveIpRequests();
    }

    private function loadIpRequests(): array
    {
        if (file_exists('ip_requests.json')) {
            return json_decode(file_get_contents('ip_requests.json'), true);
        }
        return [];
    }

    private function saveIpRequests(): void
    {
        file_put_contents('ip_requests.json', json_encode($this->ipRequests));
    }

    public function handle(): bool
    {
        $this->checkLimitIpRequests();
        return parent::handle();
    }


    private function checkLimitIpRequests(): void
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $currentTime = time();

        if (!isset($this->ipRequests[$ip])) {
            $this->ipRequests[$ip] = [];
        }

        $this->ipRequests[$ip] = array_filter($this->ipRequests[$ip], function ($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) <= $this->timeWindow;
        });

        $this->ipRequests[$ip][] = $currentTime;

        if (count($this->ipRequests[$ip]) > $this->rateLimit) {
            throw new \Exception('Limite de requisições excedido');
        }
    }
}