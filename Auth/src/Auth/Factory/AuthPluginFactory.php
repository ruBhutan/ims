<?php

namespace Auth\Factory;

use Auth\Controller\Plugin\AuthPlugin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthPluginFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
            return new AuthPlugin(
                $serviceLocator->getServiceLocator()
            );
	}
	
}