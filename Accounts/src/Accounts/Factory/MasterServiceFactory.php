<?php

namespace Accounts\Factory;

use Accounts\Service\MasterService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MasterServiceFactory implements FactoryInterface {
    /*
    * create service
    * @param ServiceLocatorInterface $serviceLocator
    *
    * @return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new MasterService(
            $serviceLocator->get('Accounts\Mapper\MasterMapperInterface')
        );
    }

}
