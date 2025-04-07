<?php

namespace Api\Business\Queues\InitialVerifications\Check\Steps;

use Api\Business\Queues\InitialVerifications\Check\Check;
use Api\Exceptions\FactoryException;
use Api\Lib\Current\Routes;
use Api\Lib\Current\User;
use Api\Lib\Utils\Headers;
use Api\Repository\Entity\Company\User\Users;
use Api\Repository\Standard\FactoryRepository;

class AuthenticateUser extends Check
{

    // public function handle(): bool
    // {
    //     $this->authenticate();
    //     return parent::handle();
    // }

    // private function authenticate(): void
    // {
    //     if ($this->isOpenRoute()) {
    //         return;
    //     }

    //     $router = Routes::getInstance()->router();
        
    //     if ($router->skipUser()) {
    //         return;
    //     }

    //     $galaxPayId = Headers::getInstance()->getUserGalaxPayId();

    //     $user = $this->userRepository()
    //         ->getByGalaxPayId($galaxPayId);

    //     if (!$user->hasData()) {
    //         $exc = 'Standard\Current\Company\User\UserNotFoundException';
    //         throw FactoryException::create($exc, [$galaxPayId]);
    //     }

    //     User::getInstance()->setUser($user);
    // }

    // private function userRepository(): Users
    // {
    //     return FactoryRepository::create(Users::class);
    // }
}