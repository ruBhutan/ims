<?php

namespace LeaveEncashment\Factory;

use LeaveEncashment\Controller\LeaveEncashmentController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LeaveEncashmentControllerFactory implements FactoryInterface
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
		$leaveService = $realServiceLocator->get('LeaveEncashment\Service\LeaveEncashmentServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new LeaveEncashmentController($leaveService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}