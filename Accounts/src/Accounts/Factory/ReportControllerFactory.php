<?php

namespace Accounts\Factory;

use Accounts\Controller\ReportController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReportControllerFactory implements FactoryInterface {

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return StudentFeeCategoryController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $service = $realServiceLocator->get('Accounts\Service\MasterServiceInterface');

        return new ReportController($service, $realServiceLocator);
    }
}
