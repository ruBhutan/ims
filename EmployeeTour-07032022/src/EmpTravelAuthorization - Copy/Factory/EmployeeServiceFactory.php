<?php

namespace EmpTravelAuthorization\Factory;

use EmpTravelAuthorization\Service\EmpTravelAuthorizationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmployeeServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new EmpTravelAuthorizationService(
			$serviceLocator->get('EmpTravelAuthorization\Mapper\EmpTravelAuthorizationMapperInterface')
		);
	}
	
}