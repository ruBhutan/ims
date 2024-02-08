<?php

namespace OrgSettings\Factory;

use OrgSettings\Controller\OrgSettingsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OrgSettingsControllerFactory implements FactoryInterface
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
		$settingsService = $realServiceLocator->get('OrgSettings\Service\OrgSettingsServiceInterface');
		$auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		
		return new OrgSettingsController($settingsService, $auditTrailService, $realServiceLocator);
	}
	
}