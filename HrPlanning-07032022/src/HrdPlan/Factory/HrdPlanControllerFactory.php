<?php

namespace HrdPlan\Factory;

use HrdPlan\Controller\HrdPlanController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HrdPlanControllerFactory implements FactoryInterface
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
		$hrdPlanService = $realServiceLocator->get('HrdPlan\Service\HrdPlanServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new hrdPlanController($hrdPlanService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}