<?php

namespace ResearchPublication\Factory;

use ResearchPublication\Service\ResearchPublicationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResearchPublicationServiceFactory implements FactoryInterface
{
	/*
	* create service
	* @param ServiceLocatorInterface $serviceLocator
	* 
	* @return mixed
	*/
	
	public function createService(ServiceLocatorInterface $serviceLocator)
	{
		return new ResearchPublicationService(
			$serviceLocator->get('ResearchPublication\Mapper\ResearchPublicationMapperInterface')
		);
	}
	
}