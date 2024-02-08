<?php

namespace Accounts\Factory;

use Accounts\Service\PayrollService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PayrollServiceFactory implements FactoryInterface {
    /*
    * create service
    * @param ServiceLocatorInterface $serviceLocator
    *
    * @return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new PayrollService(
            $serviceLocator->get('Accounts\Mapper\PayrollMapperInterface')
        );
    }

}
