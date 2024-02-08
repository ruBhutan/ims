<?php

namespace Accounts\Factory;

use Accounts\Controller\MasterController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MasterControllerFactory implements FactoryInterface {

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return FeeStructureController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $service = $realServiceLocator->get('Accounts\Service\MasterServiceInterface');

        return new MasterController($service, $realServiceLocator);
    }

}
