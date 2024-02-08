<?php

namespace HrSettings\Factory;

use HrSettings\Service\HrSettingsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HrSettingsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new HrSettingsService(
			$serviceLocator->get('HrSettings\Mapper\HrSettingsMapperInterface')
		);
	}
	
}