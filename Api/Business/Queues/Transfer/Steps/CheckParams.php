<?php

namespace Api\Business\Queues\Transfer\Steps;

use Api\Business\Queues\Transfer\Execute;
use Api\Exceptions\FactoryException;
use Api\Lib\Current\Input;
use Respect\Validation\Validator as v;

class CheckParams extends Execute
{
    public function handle(): bool
    {
        try {
            $request = Input::getInstance()->getDataBodyRequest();
            v::key('payer', v::intVal()->positive())->assert($request);
            v::key('payee', v::intVal()->positive())->assert($request);
            v::key('value', v::numericVal()->positive())->assert($request);
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            throw FactoryException::create("Transfer\InvalidFieldsException");
        }
        $this->setTransferData($request);
        return parent::handle();
    }
}
