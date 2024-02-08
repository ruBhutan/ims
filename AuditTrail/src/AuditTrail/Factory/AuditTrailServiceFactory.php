<?php

namespace AuditTrail\Factory;

use AuditTrail\Service\AuditTrailService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuditTrailServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new AuditTrailService(
			$serviceLocator->get('AuditTrail\Mapper\AuditTrailMapperInterface'),
			$serviceLocator->get('Zend\Authentication\AuthenticationService')
		);
	}
	
}