<?php

namespace PmsDates\Factory;

use PmsDates\Controller\PmsDatesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PmsDatesControllerFactory implements FactoryInterface
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
		$PmsDatessService = $realServiceLocator->get('PmsDates\Service\PmsDatesServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new PmsDatesController($PmsDatessService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}