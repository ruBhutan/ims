<?php

namespace EmployeeLeave\Factory;

use EmployeeLeave\Service\EmployeeLeaveService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmployeeLeaveServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new EmployeeLeaveService(
			$serviceLocator->get('EmployeeLeave\Mapper\EmployeeLeaveMapperInterface')
		);
	}
	
}