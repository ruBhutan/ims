<?php

namespace Accounts\Factory;

use Accounts\Service\AssetService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AssetServiceFactory implements FactoryInterface {
    /*
    * create service
    * @param ServiceLocatorInterface $serviceLocator
    *
    * @return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new AssetService(
            $serviceLocator->get('Accounts\Mapper\AssetMapperInterface')
        );
    }

}
