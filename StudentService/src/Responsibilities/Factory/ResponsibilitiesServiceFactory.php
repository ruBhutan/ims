<?php

namespace Responsibilities\Factory;

use Responsibilities\Service\ResponsibilitiesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResponsibilitiesServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ResponsibilitiesService(
			$serviceLocator->get('Responsibilities\Mapper\ResponsibilitiesMapperInterface')
		);
	}
	
}