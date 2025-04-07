<?php

namespace Api\Controller;

use Api\Business\FactoryBusiness;
use Api\Controller\Standard\Controller;

class Transfer extends Controller
{
    public function index(): void
    {
        $queue = FactoryBusiness::create('Queues\Transfer\Execute')->init();
        $this->appendResponseType(true);
    }
}   
