<?php

namespace RepeatModules\Factory;

use RepeatModules\Controller\RepeatModulesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RepeatModulesControllerFactory implements FactoryInterface
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
		$repeatModulesService = $realServiceLocator->get('RepeatModules\Service\RepeatModulesServiceInterface');
		$notificationService = $realServiceLocator->get('Notification\Service\NotificationServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new RepeatModulesController($repeatModulesService, $notificationService, $auditTrailService, $realServiceLocator);
	}
	
}