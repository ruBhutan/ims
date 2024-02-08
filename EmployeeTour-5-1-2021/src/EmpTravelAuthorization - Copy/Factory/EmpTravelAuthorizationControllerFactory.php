<?php

namespace EmpTravelAuthorization\Factory;

use EmpTravelAuthorization\Controller\EmpTravelAuthorizationController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmpTravelAuthorizationControllerFactory implements FactoryInterface
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
		$empTravelAuthorizationService = $realServiceLocator->get('EmpTravelAuthorization\Service\EmpTravelAuthorizationServiceInterface');
		
		return new EmpTravelAuthorizationController($empTravelAuthorizationService);
	}
	
}