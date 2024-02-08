<?php

namespace Acl\Factory;

use Acl\Controller\AclController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AclControllerFactory implements FactoryInterface
{
    /*
    * create service
    * @param ServiceLocatorInterface $serviceLocator
    * 
    * @return mixed
    */

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
            $realServiceLocator = $serviceLocator->getServiceLocator();
            return new AclController($realServiceLocator);
    }
}