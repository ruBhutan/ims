<?php

namespace PayrollManagement\Factory;

use PayrollManagement\Service\PayrollManagementService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PayrollManagementServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new PayrollManagementService(
			$serviceLocator->get('PayrollManagement\Mapper\PayrollManagementMapperInterface')
		);
	}
	
}