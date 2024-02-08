<?php

namespace EmployeeDetail\Factory;

use EmployeeDetail\Controller\EmployeeDetailController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmployeeDetailControllerFactory implements FactoryInterface
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
		$employeeDetailService = $realServiceLocator->get('EmployeeDetail\Service\EmployeeDetailServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new EmployeeDetailController($employeeDetailService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}