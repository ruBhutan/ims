<?php

namespace EmployeeDetail\Factory;

use EmployeeDetail\Service\EmployeeDetailService;
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
		return new EmployeeDetailService(
			$serviceLocator->get('EmployeeDetail\Mapper\EmployeeMapperInterface')
		);
	}
	
}