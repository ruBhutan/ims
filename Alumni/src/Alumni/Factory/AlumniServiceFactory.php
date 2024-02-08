<?php

namespace Alumni\Factory;

use Alumni\Service\AlumniService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AlumniServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AlumniService(
			$serviceLocator->get('Alumni\Mapper\AlumniMapperInterface')
		);
	}
	
}