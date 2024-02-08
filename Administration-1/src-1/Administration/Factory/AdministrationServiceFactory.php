<?php

namespace Administration\Factory;

use Administration\Service\AdministrationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdministrationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AdministrationService(
			$serviceLocator->get('Administration\Mapper\AdministrationMapperInterface')
		);
	}
	
}