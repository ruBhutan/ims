<?php

namespace HrmPlan\Factory;

use HrmPlan\Controller\HrmPlanController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HrmPlanControllerFactory implements FactoryInterface
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
		$hrmPlanService = $realServiceLocator->get('HrmPlan\Service\HrmPlanServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new HrmPlanController($hrmPlanService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}