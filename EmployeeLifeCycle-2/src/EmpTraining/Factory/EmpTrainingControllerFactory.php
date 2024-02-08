<?php

namespace EmpTraining\Factory;

use EmpTraining\Controller\EmpTrainingController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmpTrainingControllerFactory implements FactoryInterface
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
		$trainingService = $realServiceLocator->get('EmpTraining\Service\EmpTrainingServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new EmpTrainingController($trainingService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}