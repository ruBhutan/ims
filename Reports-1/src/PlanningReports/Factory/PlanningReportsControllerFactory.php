<?php

namespace PlanningReports\Factory;

use PlanningReports\Controller\PlanningReportsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlanningReportsControllerFactory implements FactoryInterface
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
		$publicationService = $realServiceLocator->get('PlanningReports\Service\PlanningReportsServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new PlanningReportsController($publicationService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}