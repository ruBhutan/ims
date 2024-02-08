<?php

namespace PayrollManagement\Factory;

use PayrollManagement\Controller\PayrollManagementController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PayrollManagementControllerFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		$realServiceLocator = $serviceLocator->getServiceLocator();
		$payrollService = $realServiceLocator->get('PayrollManagement\Service\PayrollManagementServiceInterface');
		
		return new PayrollManagementController($payrollService);
	}
	
}