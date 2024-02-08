<?php

namespace Planning\Factory;

use Planning\Service\PlanningService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlanningServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new PlanningService(
			$serviceLocator->get('Planning\Mapper\PlanningMapperInterface')
		);
	}
	
}