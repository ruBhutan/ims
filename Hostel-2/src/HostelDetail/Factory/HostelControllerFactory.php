<?php

namespace Responsibilities\Factory;

use Responsibilities\Controller\ResponsibilitiesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResponsibilitiesControllerFactory implements FactoryInterface
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
		$responsibilityService = $realServiceLocator->get('Responsibilities\Service\ResponsibilitiesServiceInterface');
		
		return new ResponsibilitiesController($responsibilityService);
	}
	
}