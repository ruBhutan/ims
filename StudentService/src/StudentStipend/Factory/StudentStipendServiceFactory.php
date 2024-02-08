<?php

namespace StudentStipend\Factory;

use StudentStipend\Service\StudentStipendService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentStipendServiceFactory implements FactoryInterface {
    /*
    * create service
    * @param ServiceLocatorInterface $serviceLocator
    *
    * @return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new StudentStipendService(
            $serviceLocator->get('StudentStipend\Mapper\StudentStipendMapperInterface')
        );
    }

}
