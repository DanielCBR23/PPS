<?php

namespace Api\Business\Queues\InitialVerifications\Check\Steps;

use Api\Business\FactoryBusiness;
use Api\Business\Queues\InitialVerifications\Check\Check;
use Api\Exceptions\FactoryException;
use Api\Lib\Current\Company;
use Api\Lib\Utils\Headers;
use Api\Repository\Mapper\Company\CompanyMap;

class AuthenticateInCompany extends Check
{

    // public function handle(): bool
    // {
    //     if (!$this->isOpenRoute()) {
    //         $this->authInCompanyByHeader();
    //     }
    //     return parent::handle();
    // }

    // private function authInCompanyByHeader(): void
    // {
    //     $headers = Headers::getInstance();
    //     $companyGalaxPayId = $headers->getCompanyGalaxPayId();
    //     $this->setCurrentCompany($companyGalaxPayId);
    // }

    // private function setCurrentCompany(int $companyGalaxPayId): void
    // {
    //     $company = $this->getRepository()->getByGalaxPayId($companyGalaxPayId);
    //     $this->checkCompanyExists($company);
    //     Company::getInstance()->setCompany($company);
    //     $this->checkCompanyCanUsePix();
    // }
    
    //  protected function checkCompanyExists(CompanyMap $company): void
    // {
    //     if (!$company->hasData()) {
    //         $exc = 'Company\CompanyNotFoundException';
    //         throw FactoryException::create($exc);
    //     }
    // }

    // private function checkCompanyCanUsePix(): void
    // {
    //     FactoryBusiness::create('Data\Companies\CanUsePix')->checkStandard();
    // }
}