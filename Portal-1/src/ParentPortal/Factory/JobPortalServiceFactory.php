<?php

namespace JobPortal\Factory;

use JobPortal\Service\JobPortalService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobPortalServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new JobPortalService(
			$serviceLocator->get('JobPortal\Mapper\JobPortalMapperInterface')
		);
	}
	
}