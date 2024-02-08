<?php

namespace StudentStipend\Factory;

use StudentStipend\Controller\StudentStipendController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentStipendControllerFactory implements FactoryInterface {

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return StudentStipendController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $service = $realServiceLocator->get('StudentStipend\Service\StudentStipendServiceInterface');

        return new StudentStipendController($service, $realServiceLocator);
    }

}
