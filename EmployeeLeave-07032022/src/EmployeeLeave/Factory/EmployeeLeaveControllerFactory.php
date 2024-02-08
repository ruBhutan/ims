<?php

namespace EmployeeLeave\Factory;

use EmployeeLeave\Controller\EmployeeLeaveController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmployeeLeaveControllerFactory implements FactoryInterface
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
		$leaveService = $realServiceLocator->get('EmployeeLeave\Service\EmployeeLeaveServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new EmployeeLeaveController($leaveService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}