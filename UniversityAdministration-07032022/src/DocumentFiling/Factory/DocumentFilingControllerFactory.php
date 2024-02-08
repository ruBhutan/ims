<?php

namespace DocumentFiling\Factory;

use DocumentFiling\Controller\DocumentFilingController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DocumentFilingControllerFactory implements FactoryInterface
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
		$documentFilingService = $realServiceLocator->get('DocumentFiling\Service\DocumentFilingServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new DocumentFilingController($documentFilingService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}