<?php

namespace UniversityResearch\Factory;

use UniversityResearch\Controller\UniversityResearchController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Notification\Service\NotificationServiceInterface;
use AuditTrail\Service\AuditTrailServiceInterface;

class UniversityResearchControllerFactory implements FactoryInterface
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
		$universityResearchService = $realServiceLocator->get('UniversityResearch\Service\UniversityResearchServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
                
		return new UniversityResearchController($universityResearchService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}