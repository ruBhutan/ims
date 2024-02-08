<?php

namespace AcademicAllocation\Factory;

use AcademicAllocation\Controller\AcademicAllocationController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AcademicAllocationControllerFactory implements FactoryInterface
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
		$academicAllocationService = $realServiceLocator->get('AcademicAllocation\Service\AcademicAllocationServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new AcademicAllocationController($academicAllocationService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}