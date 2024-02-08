<?php

namespace LeaveCategory\Factory;

use LeaveCategory\Controller\LeaveCategoryController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LeaveCategoryControllerFactory implements FactoryInterface
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
		$leaveService = $realServiceLocator->get('LeaveCategory\Service\LeaveCategoryServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new LeaveCategoryController($leaveService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}