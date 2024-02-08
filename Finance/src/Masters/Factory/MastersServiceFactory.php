<?php

namespace Masters\Factory;

use Masters\Service\MastersService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MastersServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new MastersService(
			$serviceLocator->get('Masters\Mapper\MastersMapperInterface')
		);
	}
	
}