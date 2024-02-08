<?php

namespace Discipline\Factory;

use Discipline\Service\DisciplineService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DisciplineServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new DisciplineService(
			$serviceLocator->get('Discipline\Mapper\DisciplineMapperInterface')
		);
	}
	
}