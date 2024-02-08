<?php

namespace Accounts\Factory;

use Accounts\Controller\TransactionController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransactionControllerFactory implements FactoryInterface {

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return StudentFeeCategoryController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $service = $realServiceLocator->get('Accounts\Service\MasterServiceInterface');

        return new TransactionController($service, $realServiceLocator);
    }

}
