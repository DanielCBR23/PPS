<?php

namespace Api\Controller;

use Api\Business\FactoryBusiness;
use Api\Controller\Standard\Controller;

class Register extends Controller
{
    public function index(): void
    {
        $queue = FactoryBusiness::create('Queues\Register\Execute')->init();
        $this->appendResponseType(true);
    }
}   
