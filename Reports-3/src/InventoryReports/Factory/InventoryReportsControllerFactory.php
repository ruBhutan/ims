<?php

namespace InventoryReports\Factory;

use InventoryReports\Controller\InventoryReportsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InventoryReportsControllerFactory implements FactoryInterface
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
		$publicationService = $realServiceLocator->get('InventoryReports\Service\InventoryReportsServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new InventoryReportsController($publicationService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}