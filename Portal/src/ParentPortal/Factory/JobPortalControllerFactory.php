<?php

namespace JobPortal\Factory;

use JobPortal\Controller\JobPortalController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobPortalControllerFactory implements FactoryInterface
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
		$jobService = $realServiceLocator->get('JobPortal\Service\JobPortalServiceInterface');
		
		return new JobPortalController($jobService);
	}
	
}