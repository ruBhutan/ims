<?php

namespace StudentFeeDetails\Factory;

use StudentFeeDetails\Controller\StudentFeeDetailsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentFeeDetailsControllerFactory implements FactoryInterface {

    /**
     * create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return StudentFeeDetailsController|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $service = $realServiceLocator->get('StudentFeeDetails\Service\StudentFeeDetailsServiceInterface');

        return new StudentFeeDetailsController($service, $realServiceLocator);
    }

}
