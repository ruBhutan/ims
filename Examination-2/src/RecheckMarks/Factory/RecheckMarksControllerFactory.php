<?php

namespace RecheckMarks\Factory;

use RecheckMarks\Controller\RecheckMarksController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RecheckMarksControllerFactory implements FactoryInterface
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
		$recheckService = $realServiceLocator->get('RecheckMarks\Service\RecheckMarksServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new RecheckMarksController($recheckService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}