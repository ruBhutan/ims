<?php

namespace Accounts\Factory;

use Accounts\Mapper\AssetDbSqlMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class AssetDbSqlMapperFactory implements FactoryInterface {
    /*
    * Create Service
    * @ param ServiceLocatorInterface $serviceLocator
    * @ return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new AssetDbSqlMapper(
            $serviceLocator->get('Zend\Db\Adapter\Adapter'),
            new ClassMethods(false)
        );
    }

}
