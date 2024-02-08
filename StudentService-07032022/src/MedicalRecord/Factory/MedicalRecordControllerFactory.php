<?php

namespace MedicalRecord\Factory;

use MedicalRecord\Controller\MedicalRecordController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MedicalRecordControllerFactory implements FactoryInterface
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
		$recordService = $realServiceLocator->get('MedicalRecord\Service\MedicalRecordServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new MedicalRecordController($recordService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}