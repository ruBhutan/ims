<?php

namespace StudentAdmission\Factory;

use StudentAdmission\Controller\StudentAdmissionController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentAdmissionControllerFactory implements FactoryInterface
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
		$studentAdmissionService = $realServiceLocator->get('StudentAdmission\Service\StudentAdmissionServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new StudentAdmissionController($studentAdmissionService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}