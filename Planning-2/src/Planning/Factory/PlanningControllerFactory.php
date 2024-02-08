<?php

namespace Planning\Factory;

use Planning\Controller\PlanningController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlanningControllerFactory implements FactoryInterface
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
		$planningService = $realServiceLocator->get('Planning\Service\PlanningServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new PlanningController($planningService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}