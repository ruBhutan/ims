<?php

namespace PmsRatings\Factory;

use PmsRatings\Controller\PmsRatingsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PmsRatingsControllerFactory implements FactoryInterface
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
		$pmssService = $realServiceLocator->get('PmsRatings\Service\PmsRatingsServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new PmsRatingsController($pmssService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}