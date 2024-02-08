<?php

namespace StudentSuggestions\Factory;

use StudentSuggestions\Controller\StudentSuggestionsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentSuggestionsControllerFactory implements FactoryInterface
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
		$studentService = $realServiceLocator->get('StudentSuggestions\Service\StudentSuggestionsServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new StudentSuggestionsController($studentService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}