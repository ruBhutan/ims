<?php

namespace EmployeeTask\Factory;

use EmployeeTask\Controller\EmployeeTaskController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmployeeTaskControllerFactory implements FactoryInterface
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
		$publicationService = $realServiceLocator->get('EmployeeTask\Service\EmployeeTaskServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new EmployeeTaskController($publicationService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}