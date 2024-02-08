<?php

namespace StudentFeeDetails\Factory;

use StudentFeeDetails\Service\StudentFeeDetailsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentFeeDetailsServiceFactory implements FactoryInterface {
    /*
    * create service
    * @param ServiceLocatorInterface $serviceLocator
    *
    * @return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new StudentFeeDetailsService(
            $serviceLocator->get('StudentFeeDetails\Mapper\StudentFeeDetailsMapperInterface')
        );
    }

}
