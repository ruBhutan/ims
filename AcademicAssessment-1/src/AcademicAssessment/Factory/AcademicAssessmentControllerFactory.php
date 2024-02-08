<?php

namespace AcademicAssessment\Factory;

use AcademicAssessment\Controller\AcademicAssessmentController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AcademicAssessmentControllerFactory implements FactoryInterface
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
		$academicAssessmentService = $realServiceLocator->get('AcademicAssessment\Service\AcademicAssessmentServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new AcademicAssessmentController($academicAssessmentService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}