<?php

namespace Hostel\Factory;

use Hostel\Service\HostelService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HostelServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new HostelService(
			$serviceLocator->get('Hostel\Mapper\HostelMapperInterface')
		);
	}
	
}