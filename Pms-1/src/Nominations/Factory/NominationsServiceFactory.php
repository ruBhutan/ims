<?php

namespace Nominations\Factory;

use Nominations\Service\NominationsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NominationsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new NominationsService(
			$serviceLocator->get('Nominations\Mapper\NominationsMapperInterface')
		);
	}
	
}