<?php

namespace UniversityAdministration\Factory;

use UniversityAdministration\Controller\UniversityAdministrationController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UniversityAdministrationControllerFactory implements FactoryInterface
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
		$universityAdministrationService = $realServiceLocator->get('UniversityAdministration\Service\UniversityAdministrationServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new UniversityAdministrationController($universityAdministrationService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}