<?php

namespace OrgSettings\Factory;

use OrgSettings\Service\OrgSettingsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OrgSettingsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new OrgSettingsService(
			$serviceLocator->get('OrgSettings\Mapper\OrgSettingsMapperInterface')
		);
	}
	
}