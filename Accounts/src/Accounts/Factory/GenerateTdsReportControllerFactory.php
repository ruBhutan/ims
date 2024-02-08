<?php

namespace Accounts\Factory;

use Accounts\Controller\GenerateTdsReportController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GenerateTdsReportControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {

        $realServiceLocator = $serviceLocator->getServiceLocator();
        $service = $realServiceLocator->get('Accounts\Service\GenerateTdsReportServiceInterface');

        return new GenerateTdsReportController($service, $realServiceLocator);
    }

}
