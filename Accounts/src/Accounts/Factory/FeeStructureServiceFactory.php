<?php

namespace Accounts\Factory;

use Accounts\Service\FeeStructureService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FeeStructureServiceFactory implements FactoryInterface {
    /*
    * create service
    * @param ServiceLocatorInterface $serviceLocator
    *
    * @return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new FeeStructureService(
            $serviceLocator->get('Accounts\Mapper\FeeStructureMapperInterface')
        );
    }

}
