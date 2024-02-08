<?php

namespace JobApplicant\Factory;

use JobApplicant\Controller\JobApplicantController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobApplicantControllerFactory implements FactoryInterface
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
		$jobApplicantService = $realServiceLocator->get('JobApplicant\Service\JobApplicantServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new JobApplicantController($jobApplicantService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}