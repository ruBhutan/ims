<?php

namespace Clubs\Factory;

use Clubs\Service\ClubsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClubsServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ClubsService(
			$serviceLocator->get('Clubs\Mapper\ClubsMapperInterface')
		);
	}
	
}