<?php

namespace RepeatModules\Factory;

use RepeatModules\Service\RepeatModulesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RepeatModulesServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new RepeatModulesService(
			$serviceLocator->get('RepeatModules\Mapper\RepeatModulesMapperInterface')
		);
	}
	
}