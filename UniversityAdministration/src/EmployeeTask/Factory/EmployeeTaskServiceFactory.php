<?php

namespace EmployeeTask\Factory;

use EmployeeTask\Service\EmployeeTaskService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmployeeTaskServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new EmployeeTaskService(
			$serviceLocator->get('EmployeeTask\Mapper\EmployeeTaskMapperInterface')
		);
	}
	
}