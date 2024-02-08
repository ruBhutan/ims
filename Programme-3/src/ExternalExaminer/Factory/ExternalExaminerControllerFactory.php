<?php

namespace ExternalExaminer\Factory;

use ExternalExaminer\Controller\ExternalExaminerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExternalExaminerControllerFactory implements FactoryInterface
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
		$externalExaminerService = $realServiceLocator->get('ExternalExaminer\Service\ExternalExaminerServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new externalExaminerController($externalExaminerService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}