<?php

namespace Accounts\Factory;

use Accounts\Service\GenerateTdsReportService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GenerateTdsReportServiceFactory implements FactoryInterface {

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return GenerateTdsReportService|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new GenerateTdsReportService(
            $serviceLocator->get('Accounts\Mapper\GenerateTdsReportMapperInterface')
        );
    }

}
