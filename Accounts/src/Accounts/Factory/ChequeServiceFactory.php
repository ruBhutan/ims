<?php

namespace Accounts\Factory;

use Accounts\Service\ChequeService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ChequeServiceFactory implements FactoryInterface {
    /*
    * create service
    * @param ServiceLocatorInterface $serviceLocator
    *
    * @return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new ChequeService(
            $serviceLocator->get('Accounts\Mapper\ChequeMapperInterface')
        );
    }

}
