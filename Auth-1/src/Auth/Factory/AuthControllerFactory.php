<?php

namespace Auth\Factory;

use Auth\Controller\AuthController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthControllerFactory implements FactoryInterface
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
        $auditTrailService = $realServiceLocator->get('AuditTrail\Service\AuditTrailServiceInterface');
		return new AuthController($realServiceLocator, $auditTrailService);
	}
	
}