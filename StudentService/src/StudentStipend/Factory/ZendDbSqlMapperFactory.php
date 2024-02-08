<?php

namespace StudentStipend\Factory;

use StudentStipend\Mapper\ZendDbSqlMapper;
use StudentStipend\Model\StudentStipend;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class ZendDbSqlMapperFactory implements FactoryInterface {
    /*
    * Create Service
    * @ param ServiceLocatorInterface $serviceLocator
    * @ return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new ZendDbSqlMapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'),
            new ClassMethods(false),
            new StudentStipend()
        );
    }

}
