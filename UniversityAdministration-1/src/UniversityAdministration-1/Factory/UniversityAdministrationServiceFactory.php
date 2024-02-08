<?php

namespace UniversityAdministration\Factory;

use UniversityAdministration\Service\UniversityAdministrationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UniversityAdministrationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new UniversityAdministrationService(
			$serviceLocator->get('UniversityAdministration\Mapper\UniversityAdministrationMapperInterface')
		);
	}
	
}