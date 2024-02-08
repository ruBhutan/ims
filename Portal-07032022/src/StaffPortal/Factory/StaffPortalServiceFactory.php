<?php

namespace StaffPortal\Factory;

use StaffPortal\Service\StaffPortalService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StaffPortalServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new StaffPortalService(
			$serviceLocator->get('StaffPortal\Mapper\StaffPortalMapperInterface')
		);
	}
	
}